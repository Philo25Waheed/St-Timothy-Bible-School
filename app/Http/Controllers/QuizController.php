<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\StudentPoint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Quiz::with(['lesson', 'creator', 'questions', 'schoolClass.grade']);

        if ($user->isStudent()) {
            $student = StudentProfile::where('user_id', $user->id)->first();
            if ($student && $student->class_id) {
                $query->where(function($q) use ($student) {
                    $q->where('class_id', $student->class_id)
                      ->orWhereNull('class_id');
                });
            } else {
                $query->whereNull('class_id');
            }
        } elseif ($user->isServant()) {
            $classIds = $user->assignedClasses->pluck('id')->toArray();
            $query->where(function($q) use ($user, $classIds) {
                $q->whereIn('class_id', $classIds)
                  ->orWhere('created_by', $user->id);
            });
        }

        $quizzes = $query->latest()->get();
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->isServant()) {
            $classes = $user->assignedClasses()->with('grade')->get();
            if ($classes->isEmpty()) {
                return redirect()->route('quizzes.index')->with('error', 'لم يتم إسناد أي فصل لك بعد لإنشاء اختبارات له. تواصل مع إدارة المدرسة.');
            }
            $gradeIds = $classes->pluck('grade_id')->filter()->unique()->toArray();
            $lessons = Lesson::whereHas('unit.curriculum', function($q) use ($gradeIds) {
                $q->whereIn('grade_id', $gradeIds);
            })->get();
            if ($lessons->isEmpty()) {
                $lessons = Lesson::all();
            }
        } else {
            $classes = SchoolClass::with('grade')->get();
            $lessons = Lesson::all();
        }

        return view('quizzes.create', compact('lessons', 'classes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'title' => 'required|string|max:255',
            'lesson_id' => 'nullable|exists:lessons,id',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
        ];

        if ($user->isServant()) {
            $assignedClassIds = $user->assignedClasses->pluck('id')->toArray();
            if (empty($assignedClassIds)) {
                return back()->with('error', 'ليس لديك فصول مسندة لإنشاء اختبار لها.');
            }
            $rules['class_id'] = 'required|in:' . implode(',', $assignedClassIds);
        } else {
            $rules['class_id'] = 'nullable|exists:classes,id';
        }

        $request->validate($rules, [
            'class_id.required' => 'يرجى تحديد الفصل الدراسي الخاص بك.',
            'class_id.in' => 'غير مصرح لك بإنشاء اختبار لفصل غير مسند لخدمتك.',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'class_id' => $request->class_id,
            'lesson_id' => $request->lesson_id,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'total_marks' => 0,
            'created_by' => $user->id,
            'is_published' => true,
        ]);

        return redirect()->route('quizzes.edit', $quiz->id)->with('success', 'تم إنشاء الاختبار بنجاح. يمكنك الآن إضافة الأسئلة وتحديد الدرجات.');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorizeQuizManagement($quiz);
        $quiz->load(['questions', 'schoolClass.grade', 'lesson']);
        return view('quizzes.builder', compact('quiz'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizManagement($quiz);

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'correct_answer' => 'required|string',
            'marks' => 'required|integer|min:1',
        ]);

        $options = null;
        if ($request->question_type === 'multiple_choice' && $request->filled('options')) {
            $options = array_filter(array_map('trim', explode(',', $request->options)));
        } elseif ($request->question_type === 'true_false') {
            $options = ['صواب', 'خطأ'];
        }

        $quiz->questions()->create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'explanation' => $request->explanation,
            'marks' => $request->marks,
        ]);

        $quiz->update(['total_marks' => $quiz->questions()->sum('marks')]);

        return back()->with('success', 'تم إضافة السؤال بنجاح.');
    }

    public function destroyQuestion(Question $question)
    {
        $quiz = $question->quiz;
        if ($quiz) {
            $this->authorizeQuizManagement($quiz);
        }
        
        $question->delete();
        if ($quiz) {
            $quiz->update(['total_marks' => $quiz->questions()->sum('marks')]);
        }
        return back()->with('success', 'تم حذف السؤال.');
    }

    public function take(Quiz $quiz)
    {
        $quiz->load('questions');
        $user = Auth::user();
        if (!$user->isStudent()) {
            return back()->with('error', 'الاختبارات مخصصة للطلاب فقط.');
        }

        $student = StudentProfile::where('user_id', $user->id)->firstOrFail();
        
        // Ensure student can only take quiz assigned to their class (or general)
        if ($quiz->class_id && $quiz->class_id !== $student->class_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الاختبار مخصص لفصل آخر.');
        }

        $previousAttempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->first();

        return view('quizzes.take', compact('quiz', 'previousAttempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $student = StudentProfile::where('user_id', $user->id)->firstOrFail();
        
        if ($quiz->class_id && $quiz->class_id !== $student->class_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الاختبار مخصص لفصل آخر.');
        }

        $quiz->load('questions');

        $userAnswers = $request->input('answers', []);
        $score = 0;
        $totalMarks = $quiz->total_marks ?: 100;

        foreach ($quiz->questions as $question) {
            $ans = $userAnswers[$question->id] ?? null;
            if ($ans !== null && trim(mb_strtolower($ans)) === trim(mb_strtolower($question->correct_answer))) {
                $score += $question->marks;
            }
        }

        $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;
        $passed = $percentage >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'score' => $score,
            'total_marks' => $totalMarks,
            'percentage' => $percentage,
            'passed' => $passed,
            'answers' => $userAnswers,
            'completed_at' => Carbon::now(),
        ]);

        if ($passed) {
            StudentPoint::create([
                'student_id' => $student->id,
                'given_by' => $quiz->created_by ?: 1,
                'amount' => 10,
                'reason' => 'اجتياز اختبار: ' . $quiz->title,
                'category' => 'quiz',
            ]);
        }

        // Notify Parent Automatically
        if ($student->parent_id) {
            Notification::create([
                'user_id' => $student->parent_id,
                'title' => 'نتيجة اختبار: ' . $student->user->name,
                'message' => 'أتم الطالب ' . $student->user->name . ' اختبار "' . $quiz->title . '" وحصل على درجة ' . $percentage . '% (' . ($passed ? 'ناجح' : 'غير ناجح') . ').',
                'type' => 'grade',
                'is_read' => false,
            ]);
        }

        return redirect()->route('quizzes.result', $attempt->id);
    }

    public function result(QuizAttempt $attempt)
    {
        $attempt->load(['quiz.questions', 'student.user']);
        return view('quizzes.result', compact('attempt'));
    }

    private function authorizeQuizManagement(Quiz $quiz): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isServant()) {
            $assignedClassIds = $user->assignedClasses->pluck('id')->toArray();
            if ($quiz->created_by === $user->id || ($quiz->class_id && in_array($quiz->class_id, $assignedClassIds))) {
                return;
            }
        }

        abort(403, 'غير مصرح لك بإدارة هذا الاختبار.');
    }
}

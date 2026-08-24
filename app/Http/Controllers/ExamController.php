<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Curriculum;
use App\Models\StudentProfile;
use App\Models\StudentPoint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Exam::with(['stage', 'grade', 'curriculum', 'questions', 'schoolClass']);

        if ($user->isStudent()) {
            $student = StudentProfile::where('user_id', $user->id)->first();
            if ($student && $student->class_id) {
                $query->where(function($q) use ($student) {
                    $q->where('class_id', $student->class_id)
                      ->orWhere('grade_id', $student->grade_id)
                      ->orWhereNull('class_id');
                });
            }
        } elseif ($user->isServant()) {
            $classIds = $user->servant_class_ids;
            $query->where(function($q) use ($user, $classIds) {
                $q->whereIn('class_id', $classIds)
                  ->orWhere('created_by', $user->id);
            });
        }

        $exams = $query->latest()->get();
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $user = Auth::user();
        $stages = Stage::with('grades')->get();
        $curricula = Curriculum::all();
        $classes = $user->isServant() ? $user->servant_classes : SchoolClass::all();

        return view('exams.create', compact('stages', 'curricula', 'classes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'title' => 'required|string|max:255',
            'stage_id' => 'nullable|exists:stages,id',
            'grade_id' => 'nullable|exists:grades,id',
            'duration_minutes' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:10|max:100',
        ];

        if ($user->isServant()) {
            $assignedClassIds = $user->servant_class_ids;
            if (empty($assignedClassIds)) {
                return back()->with('error', 'ليس لديك فصول مسندة لإنشاء امتحان لها.');
            }
            $rules['class_id'] = 'required|in:' . implode(',', $assignedClassIds);
        } else {
            $rules['class_id'] = 'nullable|exists:classes,id';
        }

        $request->validate($rules, [
            'class_id.required' => 'يرجى تحديد الفصل الدراسي الخاص بك.',
            'class_id.in' => 'غير مصرح لك بإنشاء امتحان لفصل غير مسند لخدمتك.',
        ]);

        $exam = Exam::create([
            'title' => $request->title,
            'class_id' => $request->class_id,
            'stage_id' => $request->stage_id,
            'grade_id' => $request->grade_id,
            'curriculum_id' => $request->curriculum_id,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'total_marks' => 100,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_published' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('exams.edit', $exam->id)->with('success', 'تم إنشاء الامتحان للفصل. أضف الأسئلة الآن.');
    }

    public function edit(Exam $exam)
    {
        $this->authorizeExamManagement($exam);
        $exam->load('questions');
        return view('exams.builder', compact('exam'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $this->authorizeExamManagement($exam);

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

        $exam->questions()->create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'explanation' => $request->explanation,
            'marks' => $request->marks,
        ]);

        $exam->update(['total_marks' => $exam->questions()->sum('marks')]);

        return back()->with('success', 'تم إضافة سؤال الامتحان بنجاح.');
    }

    public function take(Exam $exam)
    {
        $exam->load('questions');
        $user = Auth::user();
        if (!$user->isStudent()) {
            return back()->with('error', 'الامتحانات مخصصة للطلاب فقط.');
        }

        $student = StudentProfile::where('user_id', $user->id)->firstOrFail();

        // Ensure student can only take exam assigned to their class/grade/stage (or general)
        if ($exam->class_id && $exam->class_id !== $student->class_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لفصل دراسي آخر.');
        }
        if ($exam->grade_id && $exam->grade_id !== $student->grade_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لصف دراسي آخر.');
        }
        if ($exam->stage_id && $exam->stage_id !== $student->stage_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لمرحلة دراسية أخرى.');
        }

        $previousAttempt = ExamAttempt::where('exam_id', $exam->id)->where('student_id', $student->id)->first();

        return view('exams.take', compact('exam', 'previousAttempt'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $user = Auth::user();
        $student = StudentProfile::where('user_id', $user->id)->firstOrFail();

        // Enforce student access scope on submit
        if ($exam->class_id && $exam->class_id !== $student->class_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لفصل دراسي آخر.');
        }
        if ($exam->grade_id && $exam->grade_id !== $student->grade_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لصف دراسي آخر.');
        }
        if ($exam->stage_id && $exam->stage_id !== $student->stage_id) {
            return redirect()->route('dashboard')->with('error', 'هذا الامتحان مخصص لمرحلة دراسية أخرى.');
        }

        $exam->load('questions');

        $userAnswers = $request->input('answers', []);
        $score = 0;
        $totalMarks = $exam->total_marks ?: 100;

        foreach ($exam->questions as $question) {
            $ans = $userAnswers[$question->id] ?? null;
            if ($ans !== null && trim(mb_strtolower($ans)) === trim(mb_strtolower($question->correct_answer))) {
                $score += $question->marks;
            }
        }

        $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;
        $passed = $percentage >= $exam->passing_score;

        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
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
                'given_by' => $exam->created_by ?: 1,
                'amount' => 20,
                'reason' => 'نجاح في امتحان: ' . $exam->title,
                'category' => 'exam',
            ]);
        }

        // Notify Parent Automatically
        if ($student->parent_id) {
            Notification::create([
                'user_id' => $student->parent_id,
                'title' => 'نتيجة امتحان رسمي: ' . $student->user->name,
                'message' => 'أتم الطالب ' . $student->user->name . ' امتحان "' . $exam->title . '" وحصل على درجة ' . $percentage . '% (' . ($passed ? 'ناجح' : 'غير ناجح') . ').',
                'type' => 'grade',
                'is_read' => false,
            ]);
        }

        return redirect()->route('exams.result', $attempt->id);
    }

    public function result(ExamAttempt $attempt)
    {
        $attempt->load(['exam.questions', 'student.user', 'student.schoolClass']);
        $user = Auth::user();

        // Strict Authorization: Student, Parent of student, Servant of student's class / exam creator, or Admin
        $isAuthorized = false;

        if ($user->isAdmin()) {
            $isAuthorized = true;
        } elseif ($user->isStudent() && $attempt->student && $attempt->student->user_id === $user->id) {
            $isAuthorized = true;
        } elseif ($user->isParent() && $attempt->student && $attempt->student->parent_id === $user->id) {
            $isAuthorized = true;
        } elseif ($user->isServant()) {
            $servantClassIds = $user->servant_class_ids;
            if ($attempt->student && (
                $attempt->student->servant_id === $user->id ||
                in_array($attempt->student->class_id, $servantClassIds) ||
                $attempt->exam->created_by === $user->id
            )) {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            abort(403, 'غير مصرح لك بالاطلاع على نتيجة هذا الامتحان.');
        }

        return view('exams.result', compact('attempt'));
    }

    private function authorizeExamManagement(Exam $exam): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isServant()) {
            $assignedClassIds = $user->servant_class_ids;
            if ($exam->created_by === $user->id || ($exam->class_id && in_array($exam->class_id, $assignedClassIds))) {
                return;
            }
        }

        abort(403, 'غير مصرح لك بإدارة هذا الامتحان.');
    }
}

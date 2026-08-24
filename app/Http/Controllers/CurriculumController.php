<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Unit;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CurriculumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Curriculum::with(['stage', 'grade', 'units.lessons']);

        if ($user && $user->isStudent()) {
            $student = StudentProfile::where('user_id', $user->id)->first();
            if ($student && $student->grade_id) {
                $query->where('grade_id', $student->grade_id);
            } elseif ($student && $student->stage_id) {
                $query->where('stage_id', $student->stage_id);
            }
        } elseif ($user && $user->isServant()) {
            $assignedGradeIds = $user->servant_classes->pluck('grade_id')->filter()->unique()->toArray();
            if (!empty($assignedGradeIds)) {
                $query->where(function($q) use ($assignedGradeIds) {
                    $q->whereIn('grade_id', $assignedGradeIds)
                      ->orWhere('is_published', true);
                });
            }
        }

        $curricula = $query->get();
        return view('curriculum.index', compact('curricula'));
    }

    public function create()
    {
        $stages = Stage::with('grades')->get();
        return view('curriculum.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
        ]);

        Curriculum::create($request->only('title', 'stage_id', 'grade_id', 'description', 'is_published'));
        return redirect()->route('curriculum.index')->with('success', 'تم إنشاء المنهج بنجاح.');
    }

    public function show(Curriculum $curriculum)
    {
        $curriculum->load('units.lessons.quizzes');
        $user = Auth::user();
        $canManageCurriculum = $this->canUserManageCurriculum($user, $curriculum);

        return view('curriculum.show', compact('curriculum', 'canManageCurriculum'));
    }

    public function storeUnit(Request $request, Curriculum $curriculum)
    {
        $user = Auth::user();
        if (!$this->canUserManageCurriculum($user, $curriculum)) {
            abort(403, 'غير مصرح لك بإضافة وحدات لهذا المنهج.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'term' => 'required|in:1,2',
            'description' => 'nullable|string',
        ]);

        $curriculum->units()->create($request->only('title', 'term', 'description', 'order'));
        return back()->with('success', 'تم إضافة الوحدة الدراسية بنجاح.');
    }

    public function storeLesson(Request $request, Unit $unit)
    {
        $user = Auth::user();
        $curriculum = $unit->curriculum;
        
        if (!$this->canUserManageCurriculum($user, $curriculum)) {
            abort(403, 'غير مصرح لك بنشر دروس في هذا المنهج.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'bible_verse' => 'nullable|string|max:255',
            'memory_verse' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url|regex:/^https:\/\/(www\.)?(youtube\.com|youtu\.be|vimeo\.com|player\.vimeo\.com)\/.+/i',
        ], [
            'video_url.regex' => 'رابط الفيديو يجب أن يكون رابطاً آمناً (HTTPS) من منصة موثوقة (YouTube / Vimeo).',
        ]);

        // Sanitize content: completely strip <script> tags and blocks, allow only safe formatting tags, strip harmful event handlers
        $rawContent = $request->content;
        $cleanContent = null;
        if ($rawContent) {
            $cleanContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $rawContent);
            $cleanContent = strip_tags($cleanContent, '<p><br><b><strong><i><em><u><h2><h3><h4><h5><ul><ol><li><blockquote><span><div>');
            $cleanContent = preg_replace('/\s*on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $cleanContent);
            $cleanContent = preg_replace('/(javascript:|data:)/i', '', $cleanContent);
        }

        $unit->lessons()->create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $cleanContent,
            'bible_verse' => $request->bible_verse,
            'memory_verse' => $request->memory_verse,
            'video_url' => $request->video_url,
            'order' => $request->order ?? 1,
            'status' => $request->status ?? 'published',
        ]);

        return back()->with('success', 'تم نشر الدرس بنجاح للطلاب.');
    }

    public function showLesson(Lesson $lesson)
    {
        $lesson->load(['unit.curriculum', 'quizzes.schoolClass']);
        $user = Auth::user();
        $isCompleted = false;

        if ($user && $user->isStudent()) {
            $student = StudentProfile::where('user_id', $user->id)->first();
            if ($student) {
                $isCompleted = $lesson->isCompletedByStudent($student->id);
            }
        }

        return view('curriculum.lesson_view', compact('lesson', 'isCompleted'));
    }

    public function markCompleted(Request $request, Lesson $lesson)
    {
        $user = Auth::user();
        if (!$user->isStudent()) {
            return back()->with('error', 'هذه الخدمة للطلاب فقط.');
        }

        $student = StudentProfile::where('user_id', $user->id)->firstOrFail();

        LessonProgress::updateOrCreate(
            ['student_id' => $student->id, 'lesson_id' => $lesson->id],
            ['status' => 'completed', 'completed_at' => Carbon::now()]
        );

        return back()->with('success', 'مبروك! تم إنهاء الدرس بنجاح. 🎉');
    }

    private function canUserManageCurriculum($user, Curriculum $curriculum): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isServant()) {
            $assignedGradeIds = $user->servant_classes->pluck('grade_id')->filter()->unique()->toArray();
            if ($curriculum->grade_id && in_array($curriculum->grade_id, $assignedGradeIds)) {
                return true;
            }
        }

        return false;
    }
}

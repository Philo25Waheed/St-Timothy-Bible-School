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
        $curricula = Curriculum::with(['stage', 'grade', 'units.lessons'])->get();
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
        return view('curriculum.show', compact('curriculum'));
    }

    public function storeUnit(Request $request, Curriculum $curriculum)
    {
        $request->validate(['title' => 'required|string']);
        $curriculum->units()->create($request->only('title', 'term', 'description', 'order'));
        return back()->with('success', 'تم إضافة الوحدة بنجاح.');
    }

    public function storeLesson(Request $request, Unit $unit)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $unit->lessons()->create($request->only(
            'title', 'description', 'content', 'bible_verse',
            'memory_verse', 'video_url', 'order', 'status'
        ));

        return back()->with('success', 'تم إضافة الدرس بنجاح.');
    }

    public function showLesson(Lesson $lesson)
    {
        $lesson->load(['unit.curriculum', 'quizzes']);
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
}

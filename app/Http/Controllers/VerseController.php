<?php

namespace App\Http\Controllers;

use App\Models\BibleVerse;
use App\Models\StudentVerseProgress;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerseController extends Controller
{
    public function index()
    {
        $verses = BibleVerse::with(['stage', 'grade'])->get();
        return view('verses.index', compact('verses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'reference' => 'required|string',
        ]);

        BibleVerse::create($request->only('text', 'reference', 'stage_id', 'grade_id'));
        return back()->with('success', 'تم إضافة الآية للمكتبة بنجاح.');
    }

    public function updateProgress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'bible_verse_id' => 'required|exists:bible_verses,id',
            'status' => 'required|in:pending,in_review,completed,excellent',
            'notes' => 'nullable|string|max:500',
        ]);

        $student = StudentProfile::findOrFail($request->student_id);

        if ($user->isServant()) {
            $assignedClassIds = $user->servant_class_ids;

            $isAllowed = in_array($student->class_id, $assignedClassIds) || $student->servant_id === $user->id;
            if (!$isAllowed) {
                abort(403, 'غير مصرح لك بتسجيل تسميع لطالب خارج فصول خدمتك.');
            }
        }

        StudentVerseProgress::updateOrCreate(
            ['student_id' => $request->student_id, 'bible_verse_id' => $request->bible_verse_id],
            [
                'status' => $request->status,
                'notes' => $request->notes,
                'checked_by' => $user->id,
            ]
        );

        return back()->with('success', 'تم تحديث حالة التسميع بنجاح.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\StudentAchievement;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::withCount('students')->get();
        return view('achievements.index', compact('achievements'));
    }

    public function award(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'achievement_id' => 'required|exists:achievements,id',
        ]);

        StudentAchievement::updateOrCreate(
            ['student_id' => $request->student_id, 'achievement_id' => $request->achievement_id],
            ['awarded_at' => Carbon::now()]
        );

        return back()->with('success', 'تم منس الأوسام للطالب بنجاح 🎉');
    }
}

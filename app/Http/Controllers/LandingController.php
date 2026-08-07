<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Lesson;
use App\Models\News;
use App\Models\Event;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $stages = Stage::withCount('students')->get();
        $latestLessons = Lesson::where('status', 'published')->with('unit.curriculum')->latest()->take(3)->get();
        $latestNews = News::where('is_published', true)->latest()->take(3)->get();
        $upcomingEvents = Event::latest()->take(3)->get();

        $stats = [
            'students' => StudentProfile::count(),
            'servants' => User::where('role', 'servant')->count(),
            'lessons' => Lesson::count(),
            'events' => Event::count(),
        ];

        return view('landing', compact('stages', 'latestLessons', 'latestNews', 'upcomingEvents', 'stats'));
    }
}

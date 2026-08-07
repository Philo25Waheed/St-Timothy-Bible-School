<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\SchoolClass;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\AttendanceRecord;
use App\Models\StudentPoint;
use App\Models\Achievement;
use App\Models\BibleVerse;
use App\Models\StudentVerseProgress;
use App\Models\Notification;
use App\Models\Event;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isServant()) {
            return $this->servantDashboard();
        } elseif ($user->isStudent()) {
            return $this->studentDashboard();
        } elseif ($user->isParent()) {
            return $this->parentDashboard($request);
        }

        abort(403);
    }

    private function adminDashboard()
    {
        $stats = [
            'total_students' => StudentProfile::count(),
            'total_servants' => User::where('role', 'servant')->count(),
            'total_parents' => User::where('role', 'parent')->count(),
            'total_classes' => SchoolClass::count(),
            'total_stages' => Stage::count(),
            'total_lessons' => Lesson::count(),
            'total_exams' => Exam::count() + Quiz::count(),
            'overall_attendance_rate' => round(
                AttendanceRecord::count() > 0 
                ? (AttendanceRecord::whereIn('status', ['present', 'late'])->count() / AttendanceRecord::count()) * 100 
                : 100
            ),
        ];

        // Chart Data: Students by Stage
        $stages = Stage::withCount('students')->get();
        $stageChart = [
            'labels' => $stages->pluck('name'),
            'data' => $stages->pluck('students_count'),
        ];

        // Chart Data: Attendance Trends (Last 7 days)
        $attendanceDays = collect(range(6, 0))->map(function($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo)->toDateString();
            $total = AttendanceRecord::whereDate('date', $date)->count();
            $present = AttendanceRecord::whereDate('date', $date)->whereIn('status', ['present', 'late'])->count();
            $rate = $total > 0 ? round(($present / $total) * 100) : 100;
            return [
                'day' => Carbon::now()->subDays($daysAgo)->format('D'),
                'date' => $date,
                'rate' => $rate,
            ];
        });

        $attendanceChart = [
            'labels' => $attendanceDays->pluck('date'),
            'data' => $attendanceDays->pluck('rate'),
        ];

        // Chart Data: Class Performance
        $classes = SchoolClass::with(['students.quizAttempts'])->take(5)->get();
        $classPerformance = [
            'labels' => $classes->pluck('name'),
            'data' => $classes->map(function($c) {
                $scores = $c->students->flatMap->quizAttempts->pluck('percentage');
                return $scores->count() > 0 ? round($scores->avg(), 1) : 85;
            }),
        ];

        // Recent Activity Log
        $recentStudents = StudentProfile::with('user', 'schoolClass')->latest()->take(5)->get();
        $recentQuizAttempts = QuizAttempt::with('student.user', 'quiz')->latest()->take(5)->get();
        $recentEvents = Event::latest()->take(5)->get();

        return view('dashboards.admin', compact(
            'stats', 'stageChart', 'attendanceChart', 'classPerformance',
            'recentStudents', 'recentQuizAttempts', 'recentEvents'
        ));
    }

    private function servantDashboard()
    {
        $user = Auth::user();
        $assignedClasses = SchoolClass::where('servant_id', $user->id)->with('grade.stage')->get();
        $classIds = $assignedClasses->pluck('id');

        $studentsCount = StudentProfile::whereIn('class_id', $classIds)->count();
        $todayAttendance = AttendanceRecord::whereIn('class_id', $classIds)
            ->whereDate('date', Carbon::today())
            ->get();
        
        $todayPresent = $todayAttendance->whereIn('status', ['present', 'late'])->count();
        $todayTotal = $todayAttendance->count();
        $todayAttendanceRate = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100) : 100;

        $classStudents = StudentProfile::whereIn('class_id', $classIds)
            ->with(['user', 'schoolClass', 'points', 'achievements'])
            ->get();

        $upcomingQuizzes = Quiz::where('created_by', $user->id)->with('lesson')->latest()->take(5)->get();
        $upcomingEvents = Event::latest()->take(5)->get();

        return view('dashboards.servant', compact(
            'assignedClasses', 'studentsCount', 'todayAttendanceRate',
            'todayTotal', 'todayPresent', 'classStudents', 'upcomingQuizzes', 'upcomingEvents'
        ));
    }

    private function studentDashboard()
    {
        $user = Auth::user();
        $student = StudentProfile::where('user_id', $user->id)
            ->with(['stage', 'grade', 'schoolClass', 'achievements', 'points'])
            ->firstOrFail();

        // Overall Curriculum Progress
        $totalLessons = Lesson::whereHas('unit.curriculum', function($q) use ($student) {
            $q->where('grade_id', $student->grade_id);
        })->count();

        $completedLessonsCount = LessonProgress::where('student_id', $student->id)
            ->where('status', 'completed')
            ->count();

        $curriculumProgress = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

        // Next/Current Lesson
        $completedLessonIds = LessonProgress::where('student_id', $student->id)
            ->where('status', 'completed')
            ->pluck('lesson_id');

        $nextLesson = Lesson::whereHas('unit.curriculum', function($q) use ($student) {
            $q->where('grade_id', $student->grade_id);
        })->whereNotIn('id', $completedLessonIds)
          ->orderBy('order')
          ->first() ?: Lesson::first();

        // Stats
        $attendanceRate = $student->attendance_rate;
        $averageGrade = $student->average_grade;
        $totalPoints = $student->total_points;

        // Upcoming Class Quizzes & Exams assigned to student's class
        $upcomingClassQuizzes = Quiz::where(function($q) use ($student) {
            $q->where('class_id', $student->class_id)->orWhereNull('class_id');
        })->where('is_published', true)->latest()->take(5)->get();

        $upcomingClassExams = Exam::where(function($q) use ($student) {
            $q->where('class_id', $student->class_id)->orWhere('grade_id', $student->grade_id)->orWhereNull('class_id');
        })->where('is_published', true)->latest()->take(5)->get();

        // Recent Quiz/Exam Results
        $recentQuizzes = QuizAttempt::where('student_id', $student->id)->with('quiz')->latest()->take(3)->get();
        $recentExams = ExamAttempt::where('student_id', $student->id)->with('exam')->latest()->take(3)->get();

        // Weekly Memory Verse
        $weeklyVerse = BibleVerse::latest()->first();

        // Streak calculation (mock 5 weeks consecutive)
        $streakWeeks = 5;

        // Notifications
        $notifications = Notification::where('user_id', $user->id)->latest()->take(5)->get();

        return view('dashboards.student', compact(
            'student', 'curriculumProgress', 'completedLessonsCount', 'totalLessons',
            'nextLesson', 'attendanceRate', 'averageGrade', 'totalPoints',
            'upcomingClassQuizzes', 'upcomingClassExams',
            'recentQuizzes', 'recentExams', 'weeklyVerse', 'streakWeeks', 'notifications'
        ));
    }

    private function parentDashboard(Request $request)
    {
        $user = Auth::user();
        $children = StudentProfile::where('parent_id', $user->id)
            ->with(['user', 'grade', 'schoolClass', 'servantUser', 'points', 'achievements'])
            ->get();

        if ($children->isEmpty()) {
            return view('dashboards.parent_empty');
        }

        $selectedChildId = $request->query('child_id', $children->first()->id);
        $selectedChild = $children->firstWhere('id', $selectedChildId) ?: $children->first();

        $attendanceRecords = AttendanceRecord::where('student_id', $selectedChild->id)
            ->latest()
            ->take(10)
            ->get();

        $quizAttempts = QuizAttempt::where('student_id', $selectedChild->id)->with('quiz')->latest()->get();
        $examAttempts = ExamAttempt::where('student_id', $selectedChild->id)->with('exam')->latest()->get();

        $verseProgress = StudentVerseProgress::where('student_id', $selectedChild->id)
            ->with('bibleVerse')
            ->get();

        $notifications = Notification::where('user_id', $user->id)->latest()->take(5)->get();

        return view('dashboards.parent', compact(
            'children', 'selectedChild', 'attendanceRecords',
            'quizAttempts', 'examAttempts', 'verseProgress', 'notifications'
        ));
    }
}

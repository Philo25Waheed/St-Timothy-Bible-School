<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\SchoolClass;
use App\Models\Stage;
use App\Models\AttendanceRecord;
use App\Models\QuizAttempt;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function studentReport(Request $request)
    {
        $students = StudentProfile::with('user')->get();
        $selectedStudent = null;

        if ($request->filled('student_id')) {
            $selectedStudent = StudentProfile::with([
                'user', 'stage', 'grade', 'schoolClass', 'parentUser', 'servantUser',
                'attendanceRecords', 'quizAttempts.quiz', 'examAttempts.exam', 'points', 'achievements'
            ])->find($request->student_id);
        }

        return view('reports.student', compact('students', 'selectedStudent'));
    }

    public function classReport(Request $request)
    {
        $classes = SchoolClass::with(['grade.stage', 'servant'])->get();
        $selectedClass = null;
        $classStats = [];

        if ($request->filled('class_id')) {
            $selectedClass = SchoolClass::with(['students.user', 'students.quizAttempts', 'students.attendanceRecords'])
                ->find($request->class_id);

            if ($selectedClass) {
                $totalStudents = $selectedClass->students->count();
                $attendanceRecords = AttendanceRecord::where('class_id', $selectedClass->id)->get();
                $totalAttendance = $attendanceRecords->count();
                $presentCount = $attendanceRecords->whereIn('status', ['present', 'late'])->count();
                $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 100;

                $allQuizScores = $selectedClass->students->flatMap->quizAttempts->pluck('percentage');
                $avgQuizScore = $allQuizScores->count() > 0 ? round($allQuizScores->avg(), 1) : 0;

                $classStats = [
                    'total_students' => $totalStudents,
                    'attendance_rate' => $attendanceRate,
                    'avg_quiz_score' => $avgQuizScore,
                ];
            }
        }

        return view('reports.class', compact('classes', 'selectedClass', 'classStats'));
    }

    public function attendanceReport(Request $request)
    {
        $query = AttendanceRecord::with(['student.user', 'schoolClass', 'recorder']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $records = $query->latest()->paginate(20)->withQueryString();
        $classes = SchoolClass::all();

        return view('reports.attendance', compact('records', 'classes'));
    }

    public function examReport(Request $request)
    {
        $quizAttempts = QuizAttempt::with(['student.user', 'quiz'])->latest()->take(20)->get();
        $examAttempts = ExamAttempt::with(['student.user', 'exam'])->latest()->take(20)->get();

        $totalQuiz = QuizAttempt::count();
        $passedQuiz = QuizAttempt::where('passed', true)->count();
        $quizPassRate = $totalQuiz > 0 ? round(($passedQuiz / $totalQuiz) * 100) : 100;

        $totalExam = ExamAttempt::count();
        $passedExam = ExamAttempt::where('passed', true)->count();
        $examPassRate = $totalExam > 0 ? round(($passedExam / $totalExam) * 100) : 100;

        return view('reports.exam', compact('quizAttempts', 'examAttempts', 'quizPassRate', 'examPassRate'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\AttendanceRecord;
use App\Models\QuizAttempt;
use App\Models\ExamAttempt;
use App\Models\StudentPoint;
use App\Models\StudentVerseProgress;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentProfile::with(['user', 'stage', 'grade', 'schoolClass', 'parentUser', 'servantUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('code', 'like', "%{$search}%");
        }

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $stages = Stage::all();
        $classes = SchoolClass::all();

        return view('admin.students.index', compact('students', 'stages', 'classes'));
    }

    public function create()
    {
        $stages = Stage::with('grades.classes')->get();
        $classes = SchoolClass::all();
        $servants = User::where('role', 'servant')->get();
        $parents = User::where('role', 'parent')->get();
        return view('admin.students.create', compact('stages', 'classes', 'servants', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'class_id' => 'required|exists:classes,id',
            'parent_id' => 'nullable|exists:users,id',
            'servant_id' => 'nullable|exists:users,id',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
        ], [
            'name.required' => 'اسم الطالب مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'stage_id.required' => 'المرحلة الدراسية مطلوبة.',
            'grade_id.required' => 'الصف الدراسي مطلوب.',
            'class_id.required' => 'الفصل مطلوب.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_active' => true,
        ]);

        StudentProfile::create([
            'user_id' => $user->id,
            'stage_id' => $request->stage_id,
            'grade_id' => $request->grade_id,
            'class_id' => $request->class_id,
            'parent_id' => $request->parent_id,
            'servant_id' => $request->servant_id,
            'code' => 'STU-' . rand(1000, 9999),
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح.');
    }

    public function show(StudentProfile $student)
    {
        $student->load([
            'user', 'stage', 'grade', 'schoolClass', 'parentUser', 'servantUser',
            'achievements', 'points.giver', 'attendanceRecords.recorder',
            'quizAttempts.quiz', 'examAttempts.exam', 'verseProgress.bibleVerse',
            'lessonProgress.lesson'
        ]);

        return view('admin.students.show', compact('student'));
    }

    public function edit(StudentProfile $student)
    {
        $student->load('user');
        $stages = Stage::all();
        $grades = Grade::all();
        $classes = SchoolClass::all();
        $servants = User::where('role', 'servant')->get();
        $parents = User::where('role', 'parent')->get();
        return view('admin.students.edit', compact('student', 'stages', 'grades', 'classes', 'servants', 'parents'));
    }

    public function update(Request $request, StudentProfile $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        if ($request->filled('password')) {
            $student->user->update(['password' => Hash::make($request->password)]);
        }

        $student->update([
            'stage_id' => $request->stage_id,
            'grade_id' => $request->grade_id,
            'class_id' => $request->class_id,
            'parent_id' => $request->parent_id,
            'servant_id' => $request->servant_id,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح.');
    }

    public function destroy(StudentProfile $student)
    {
        $user = $student->user;
        $student->delete();
        if ($user) {
            $user->delete();
        }
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح.');
    }
}

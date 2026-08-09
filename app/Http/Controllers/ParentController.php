<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index()
    {
        $parents = User::where('role', 'parent')
            ->with(['children.user', 'children.grade'])
            ->latest()
            ->paginate(10);

        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        $unlinkedStudents = StudentProfile::with('user')->whereNull('parent_id')->get();
        return view('admin.parents.create', compact('unlinkedStudents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'gender' => 'required|in:male,female',
        ], [
            'name.required' => 'اسم ولي الأمر مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        $parent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'parent',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_active' => true,
        ]);

        if ($request->filled('student_ids')) {
            StudentProfile::whereIn('id', $request->student_ids)->update(['parent_id' => $parent->id]);
        }

        return redirect()->route('parents.index')->with('success', 'تم إضافة ولي الأمر بنجاح.');
    }

    public function edit(User $parent)
    {
        if (!$parent->isParent()) abort(404);
        $allStudents = StudentProfile::with('user')->get();
        $parentChildIds = $parent->children->pluck('id')->toArray();
        return view('admin.parents.edit', compact('parent', 'allStudents', 'parentChildIds'));
    }

    public function update(Request $request, User $parent)
    {
        if (!$parent->isParent()) abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->id,
            'phone' => 'nullable|string',
        ]);

        $parent->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $parent->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('student_ids')) {
            StudentProfile::where('parent_id', $parent->id)->update(['parent_id' => null]);
            StudentProfile::whereIn('id', $request->student_ids)->update(['parent_id' => $parent->id]);
        }

        return redirect()->route('parents.index')->with('success', 'تم تعديل بيانات ولي الأمر بنجاح.');
    }

    public function destroy(User $parent)
    {
        if (!$parent->isParent()) abort(404);
        StudentProfile::where('parent_id', $parent->id)->update(['parent_id' => null]);
        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'تم حذف حساب ولي الأمر بنجاح.');
    }

    /**
     * Parent Weekly Digest Page
     */
    public function weeklyDigest()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Get parent children
        $children = StudentProfile::where('parent_id', $user->id)
            ->with(['user', 'grade', 'schoolClass', 'points', 'attendanceRecords', 'verseProgress.verse', 'quizAttempts.quiz', 'examAttempts.exam'])
            ->get();

        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::THURSDAY);

        $childrenData = [];

        foreach ($children as $child) {
            // Weekly attendance
            $weeklyAttendance = $child->attendanceRecords()
                ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->get();

            $presentDays = $weeklyAttendance->where('status', 'present')->count();
            $absentDays = $weeklyAttendance->where('status', 'absent')->count();

            // Weekly points
            $weeklyPoints = $child->points()
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('amount');

            $totalPoints = $child->points()->sum('amount');

            // Verses progress
            $completedVerses = $child->verseProgress()
                ->where('status', 'completed')
                ->with('verse')
                ->get();

            // Recent Quizzes
            $recentQuizzes = $child->quizAttempts()
                ->with('quiz')
                ->latest()
                ->take(5)
                ->get();

            // Direct WhatsApp alert link for absence or progress share
            $whatsappMessage = \App\Services\WhatsAppNotificationService::buildAbsenceMessage(
                $user->name,
                $child->user->name,
                $child->schoolClass?->name ?? 'مدرسة القديس تيموثاوس للكتاب المقدس',
                \Carbon\Carbon::today()->format('Y-m-d')
            );
            $whatsappLink = \App\Services\WhatsAppNotificationService::generateLink($user->phone ?? '01000000000', $whatsappMessage);

            $childrenData[] = [
                'profile' => $child,
                'user' => $child->user,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'weekly_points' => $weeklyPoints,
                'total_points' => $totalPoints,
                'completed_verses' => $completedVerses,
                'recent_quizzes' => $recentQuizzes,
                'whatsapp_link' => $whatsappLink,
            ];
        }

        return view('parent.weekly_digest', compact('user', 'childrenData', 'startOfWeek', 'endOfWeek'));
    }
}

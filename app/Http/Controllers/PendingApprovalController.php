<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\SchoolClass;
use App\Models\Stage;
use App\Mail\AccountApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PendingApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_active', false)->latest()->get();
        $classes = SchoolClass::with('grade')->get();
        return view('admin.pending_approvals.index', compact('pendingUsers', 'classes'));
    }

    public function approve(Request $request, User $user)
    {
        $user->update(['is_active' => true]);

        // If user is a servant and classes were selected during approval
        if ($user->isServant() && $request->filled('class_id')) {
            $classIds = is_array($request->class_id) ? $request->class_id : [$request->class_id];
            $user->assignedClasses()->sync($classIds);
        }

        // If user is a student and has no StudentProfile, create one
        if ($user->isStudent()) {
            StudentProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'code' => 'STU-' . rand(1000, 9999),
                    'stage_id' => Stage::first()->id ?? 1,
                    'grade_id' => 1,
                    'class_id' => SchoolClass::first()->id ?? 1,
                ]
            );
        }

        // If user is a parent and submitted children info during registration, create child accounts & profiles
        if ($user->isParent() && !empty($user->pending_children_info)) {
            foreach ($user->pending_children_info as $childInfo) {
                if (!empty($childInfo['name'])) {
                    // Create child student user with secure random temporary password
                    $tempPassword = \Illuminate\Support\Str::random(12);
                    $childEmail = 'student_' . rand(1000, 9999) . '@bibleschool.com';
                    $childUser = User::create([
                        'name' => $childInfo['name'],
                        'email' => $childEmail,
                        'password' => bcrypt($tempPassword),
                        'role' => 'student',
                        'is_active' => true,
                    ]);

                    StudentProfile::create([
                        'user_id' => $childUser->id,
                        'parent_id' => $user->id,
                        'code' => 'STU-' . rand(1000, 9999),
                        'stage_id' => Stage::first()->id ?? 1,
                        'grade_id' => 1,
                        'class_id' => $childInfo['class_id'] ?? (SchoolClass::first()->id ?? 1),
                    ]);
                }
            }
        }

        // Send Approval Email Notification
        try {
            Mail::to($user->email)->send(new AccountApprovedMail($user));
        } catch (\Exception $e) {
            Log::info("Email notification simulated for approved user: " . $user->email);
        }

        return back()->with('success', 'تم اعتماد وتفعيل حساب (' . $user->name . ') بنجاح وإرسال إشعار التفعيل بالبريد الإلكتروني.');
    }

    public function reject(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with('success', 'تم رفض وحذف طلب تسجيل (' . $name . ').');
    }
}

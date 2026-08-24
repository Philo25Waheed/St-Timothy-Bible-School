<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->latest()
            ->paginate(15);

        $totalAdmins = User::where('role', 'admin')->count();
        $activeAdmins = User::where('role', 'admin')->where('is_active', true)->count();

        return view('admin.admins.index', compact('admins', 'totalAdmins', 'activeAdmins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
        ], [
            'name.required' => 'اسم مسئول النظام مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح.',
            'email.unique' => 'البريد الإلكتروني مُسجل بالفعل لمستخدم آخر.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'gender.required' => 'تحديد النوع مطلوب.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_active' => true,
        ]);

        return redirect()->route('admins.index')->with('success', 'تم إضافة مسئول النظام بنجاح 🎉');
    }

    public function edit(User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'اسم مسئول النظام مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مُسجل بالفعل لمستخدم آخر.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : $admin->is_active,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admins.index')->with('success', 'تم تعديل بيانات مسئول النظام بنجاح ✅');
    }

    public function destroy(User $admin)
    {
        if (!$admin->isAdmin()) {
            abort(404);
        }

        // Security check 1: Prevent self-deletion
        if ($admin->id === Auth::id()) {
            return back()->with('error', '⚠️ لا يمكنك حذف حسابك الشخصي الحالي أثناء تسجيل الدخول به.');
        }

        // Security check 2: Prevent deleting the last remaining admin
        $adminsCount = User::where('role', 'admin')->count();
        if ($adminsCount <= 1) {
            return back()->with('error', '⚠️ لا يمكن حذف هذا الحساب لأنه المسئول الوحيد المتبقي للنظام.');
        }

        $adminName = $admin->name;
        $admin->delete();

        return redirect()->route('admins.index')->with('success', "تم حذف مسئول النظام ({$adminName}) بنجاح.");
    }
}

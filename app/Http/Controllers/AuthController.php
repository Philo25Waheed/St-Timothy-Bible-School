<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stage;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $stages = Stage::with('grades')->get();
        $classes = SchoolClass::with('grade')->get();
        return view('auth.register', compact('stages', 'classes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:student,parent',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:500',
        ], [
            'name.required' => 'الاسم كاملاً مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'role.in' => 'غير مصرح بالتسجيل الذاتي لهذه الرتبة. يرجى مراجعة إدارة المدرسة.',
            'gender.required' => 'يرجى تحديد النوع.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'birth_date.required' => 'تاريخ الميلاد مطلوب.',
            'address.required' => 'العنوان مطلوب.',
        ]);

        // Process Parent Children Info if applicable
        $pendingChildrenInfo = [];
        if ($request->role === 'parent' && $request->has('child_name')) {
            $names = $request->input('child_name', []);
            $classes = $request->input('child_class_id', []);
            foreach ($names as $idx => $cName) {
                if (!empty(trim($cName))) {
                    $pendingChildrenInfo[] = [
                        'name' => trim($cName),
                        'class_id' => $classes[$idx] ?? null,
                    ];
                }
            }
        }

        // Create User (is_active = false for non-admin requests)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'pending_children_info' => $pendingChildrenInfo,
            'is_active' => false, // Approval required by Admin
        ]);

        // If student, create preliminary profile
        if ($request->role === 'student') {
            StudentProfile::create([
                'user_id' => $user->id,
                'code' => 'STU-' . rand(1000, 9999),
                'stage_id' => $request->stage_id ?: (Stage::first()->id ?? 1),
                'grade_id' => 1,
                'class_id' => $request->class_id ?: (SchoolClass::first()->id ?? 1),
            ]);
        }

        return redirect()->route('login')->with('success', 'تم تقديم طلب إنشاء الحساب بنجاح! 🚀 حسابك حالياً قيد المراجعة والاعتماد من قِبل إدارة مدرسة القديس تيموثاوس للكتاب المقدس. سيتم تفعيل حسابك وإخطارك عبر البريد الإلكتروني فور الموافقة.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => '⏳ حسابك قيد المراجعة والاعتماد من قِبل إدارة مدرسة القديس تيموثاوس للكتاب المقدس ولم يتم تفعيله بعد. سيتم تفعيل حسابك وإخطارك عبر البريد الإلكتروني فور الموافقة.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد هذه غير متطابقة مع سجلاتنا.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
        ], [
            'name.required' => 'الاسم مطلوب.',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }
}

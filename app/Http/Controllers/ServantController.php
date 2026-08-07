<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ServantController extends Controller
{
    public function index()
    {
        $servants = User::where('role', 'servant')
            ->with('assignedClasses')
            ->latest()
            ->paginate(10);

        return view('admin.servants.index', compact('servants'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('admin.servants.create', compact('classes'));
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
            'name.required' => 'اسم الخادم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        $servant = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'servant',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_active' => true,
        ]);

        if ($request->filled('class_ids')) {
            $servant->assignedClasses()->sync($request->class_ids);
        }

        return redirect()->route('servants.index')->with('success', 'تم إضافة الخادم بنجاح.');
    }

    public function edit(User $servant)
    {
        if (!$servant->isServant()) abort(404);
        $classes = SchoolClass::all();
        return view('admin.servants.edit', compact('servant', 'classes'));
    }

    public function update(Request $request, User $servant)
    {
        if (!$servant->isServant()) abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $servant->id,
            'phone' => 'nullable|string',
            'gender' => 'required|in:male,female',
        ]);

        $servant->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        if ($request->filled('password')) {
            $servant->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('class_ids')) {
            $servant->assignedClasses()->sync($request->input('class_ids', []));
        }

        return redirect()->route('servants.index')->with('success', 'تم تعديل بيانات الخادم بنجاح.');
    }

    public function destroy(User $servant)
    {
        if (!$servant->isServant()) abort(404);
        SchoolClass::where('servant_id', $servant->id)->update(['servant_id' => null]);
        $servant->delete();
        return redirect()->route('servants.index')->with('success', 'تم حذف الخادم بنجاح.');
    }
}

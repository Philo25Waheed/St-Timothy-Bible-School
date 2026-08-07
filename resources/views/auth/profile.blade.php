@extends('layouts.app')
@section('title', 'الملف الشخصي')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إعدادات الملف الشخصي</h1>
        <p class="page-subtitle">تعديل بيانات الحساب وكلمة المرور</p>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-user-gear"></i> البيانات الشخصية</h3>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">الاسم الكامل</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني (غير قابل للتعديل)</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background: #f1f5f9;">
            </div>
            <div class="form-group">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="form-group">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-control">
                    <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ التغييرات</button>
        </form>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-key"></i> تغيير كلمة المرور</h3>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">كلمة المرور الحالية</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">كلمة المرور الجديدة</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-accent"><i class="fas fa-lock"></i> تحديث كلمة المرور</button>
        </form>
    </div>
</div>
@endsection

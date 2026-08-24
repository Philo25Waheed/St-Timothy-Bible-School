@extends('layouts.app')
@section('title', 'تعديل بيانات مسئول النظام')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-user-pen" style="color: var(--primary);"></i> تعديل بيانات مسئول النظام</h1>
        <p class="page-subtitle">تعديل بيانات الحساب الإداري: {{ $admin->name }}</p>
    </div>
    <a href="{{ route('admins.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-right"></i> العودة لقائمة المسئولين
    </a>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <form action="{{ route('admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">الاسم بالكامل <span style="color: var(--danger);">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">البريد الإلكتروني <span style="color: var(--danger);">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="grid grid-cols-2" style="gap: 15px;">
            <div class="form-group">
                <label class="form-label">كلمة مرور جديدة (اتركها فارغة إذا لم ترد التغيير)</label>
                <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل">
            </div>
            <div class="form-group">
                <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور">
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap: 15px;">
            <div class="form-group">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
            </div>
            <div class="form-group">
                <label class="form-label">النوع <span style="color: var(--danger);">*</span></label>
                <select name="gender" class="form-control" required>
                    <option value="male" {{ old('gender', $admin->gender) === 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ old('gender', $admin->gender) === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-top: 10px;">
            <label class="form-label">حالة الحساب</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $admin->is_active) ? 'selected' : '' }}>مفعل ونشط</option>
                <option value="0" {{ !old('is_active', $admin->is_active) ? 'selected' : '' }}>معطل مؤقتاً</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
            <a href="{{ route('admins.index') }}" class="btn btn-outline">إلغاء</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-floppy-disk"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection

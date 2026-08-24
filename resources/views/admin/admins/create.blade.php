@extends('layouts.app')
@section('title', 'إضافة مسئول نظام جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-user-plus" style="color: var(--primary);"></i> إضافة مسئول نظام جديد (Admin)</h1>
        <p class="page-subtitle">إنشاء حساب إداري جديد يتمتع بكافة صلاحيات إدارة المدرسة والنظام</p>
    </div>
    <a href="{{ route('admins.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-right"></i> العودة لقائمة المسئولين
    </a>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <form action="{{ route('admins.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">الاسم بالكامل <span style="color: var(--danger);">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="مثال: د. يوسف صبحي" required>
        </div>

        <div class="form-group">
            <label class="form-label">البريد الإلكتروني (لتسجيل الدخول) <span style="color: var(--danger);">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@bibleschool.com" required>
        </div>

        <div class="grid grid-cols-2" style="gap: 15px;">
            <div class="form-group">
                <label class="form-label">كلمة المرور <span style="color: var(--danger);">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل" required>
            </div>
            <div class="form-group">
                <label class="form-label">تأكيد كلمة المرور <span style="color: var(--danger);">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="أعد كتابة كلمة المرور" required>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap: 15px;">
            <div class="form-group">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="012XXXXXXXX">
            </div>
            <div class="form-group">
                <label class="form-label">النوع <span style="color: var(--danger);">*</span></label>
                <select name="gender" class="form-control" required>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
        </div>

        <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-sm); padding: 12px 16px; margin: 20px 0; font-size: 13px; color: #b45309;">
            <i class="fas fa-shield-halved" style="margin-left: 6px;"></i>
            <strong>ملاحظة أمنية:</strong> سيتم منح هذا الحساب صلاحيات الإدارة الكاملة للتحكم في كافة الطلاب، الخدام، الفصول، الامتحانات، والإعدادات.
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('admins.index') }}" class="btn btn-outline">إلغاء</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-floppy-disk"></i> حفظ وإضافة المسئول
            </button>
        </div>
    </form>
</div>
@endsection

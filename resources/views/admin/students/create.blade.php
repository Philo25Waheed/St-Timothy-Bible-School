@extends('layouts.app')
@section('title', 'إضافة طالب جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إضافة طالب جديد</h1>
        <p class="page-subtitle">إنشاء حساب بروفايل طالب جديد في النظام</p>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">الاسم الكامل للطالب</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-control">
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">المرحلة الدراسية</label>
                <select name="stage_id" class="form-control" required>
                    @foreach($stages as $stg)
                        <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الصف الدراسي</label>
                <select name="grade_id" class="form-control" required>
                    @foreach($stages->flatMap->grades as $grd)
                        <option value="{{ $grd->id }}">{{ $grd->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الفصل</label>
                <select name="class_id" class="form-control" required>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">ولي الأمر المرتبط</label>
                <select name="parent_id" class="form-control">
                    <option value="">بدون تحديد</option>
                    @foreach($parents as $pr)
                        <option value="{{ $pr->id }}">{{ $pr->name }} ({{ $pr->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الخادم المسؤول</label>
                <select name="servant_id" class="form-control">
                    <option value="">بدون تحديد</option>
                    @foreach($servants as $srv)
                        <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="date" name="birth_date" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">ملاحظات عن الطالب</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ بيانات الطالب</button>
    </form>
</div>
@endsection

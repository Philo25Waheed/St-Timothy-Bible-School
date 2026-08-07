@extends('layouts.app')
@section('title', 'تعديل بيانات الطالب')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تعديل بيانات الطالب: {{ $student->user->name }}</h1>
        <p class="page-subtitle">تحديث السجل الأكاديمي والحساب</p>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">الاسم الكامل للطالب</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $student->user->email) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">كلمة مرور جديدة (اختياري)</label>
                <input type="password" name="password" class="form-control" placeholder="اتركه فارغاً لعدم التغيير">
            </div>
            <div class="form-group">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-control">
                    <option value="male" {{ $student->user->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ $student->user->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">المرحلة الدراسية</label>
                <select name="stage_id" class="form-control" required>
                    @foreach($stages as $stg)
                        <option value="{{ $stg->id }}" {{ $student->stage_id == $stg->id ? 'selected' : '' }}>{{ $stg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الصف الدراسي</label>
                <select name="grade_id" class="form-control" required>
                    @foreach($grades as $grd)
                        <option value="{{ $grd->id }}" {{ $student->grade_id == $grd->id ? 'selected' : '' }}>{{ $grd->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الفصل</label>
                <select name="class_id" class="form-control" required>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ $student->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">ولي الأمر</label>
                <select name="parent_id" class="form-control">
                    <option value="">بدون تحديد</option>
                    @foreach($parents as $pr)
                        <option value="{{ $pr->id }}" {{ $student->parent_id == $pr->id ? 'selected' : '' }}>{{ $pr->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> تحديث البيانات</button>
    </form>
</div>
@endsection

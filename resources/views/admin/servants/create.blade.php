@extends('layouts.app')
@section('title', 'إضافة خادم جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إضافة خادم جديد</h1>
        <p class="page-subtitle">إنشاء حساب خادم وتعيين الفصول الخاصة به</p>
    </div>
    <a href="{{ route('servants.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('servants.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">الاسم الكامل للخادم</label>
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
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label class="form-label">الجنس</label>
            <select name="gender" class="form-control">
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">الفصول المسندة (اختياري)</label>
            <select name="class_ids[]" class="form-control" multiple style="height: 100px;">
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ الخادم</button>
    </form>
</div>
@endsection

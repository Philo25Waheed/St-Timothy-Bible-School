@extends('layouts.app')
@section('title', 'إضافة ولي أمر جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إضافة ولي أمر جديد</h1>
        <p class="page-subtitle">ربطه بأبنائه الطلاب في النظام</p>
    </div>
    <a href="{{ route('parents.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('parents.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">اسم ولي الأمر</label>
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
            <label class="form-label">اختيار الأبناء لربطهم (اختياري)</label>
            <select name="student_ids[]" class="form-control" multiple style="height: 120px;">
                @foreach($unlinkedStudents as $st)
                    <option value="{{ $st->id }}">{{ $st->user->name }} (كود: {{ $st->code }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ ولي الأمر</button>
    </form>
</div>
@endsection

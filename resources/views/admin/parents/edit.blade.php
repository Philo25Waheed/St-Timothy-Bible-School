@extends('layouts.app')
@section('title', 'تعديل بيانات ولي الأمر')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تعديل ولي الأمر: {{ $parent->name }}</h1>
    </div>
    <a href="{{ route('parents.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('parents.update', $parent->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">الاسم الكامل</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $parent->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $parent->email) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">كلمة مرور جديدة (اختياري)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $parent->phone) }}">
        </div>
        <div class="form-group">
            <label class="form-label">تحديث الأبناء المرتبطين بولي الأمر</label>
            <select name="student_ids[]" class="form-control" multiple style="height: 120px;">
                @foreach($allStudents as $st)
                    <option value="{{ $st->id }}" {{ in_array($st->id, $parentChildIds) ? 'selected' : '' }}>
                        {{ $st->user->name }} (كود: {{ $st->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> تحديث البيانات</button>
    </form>
</div>
@endsection

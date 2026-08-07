@extends('layouts.app')
@section('title', 'تعديل بيانات الخادم')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تعديل بيانات الخادم: {{ $servant->name }}</h1>
    </div>
    <a href="{{ route('servants.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('servants.update', $servant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">الاسم الكامل للخادم</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $servant->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $servant->email) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">كلمة مرور جديدة (اختياري)</label>
            <input type="password" name="password" class="form-control" placeholder="اتركه فارغاً للتغاضي">
        </div>
        <div class="form-group">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $servant->phone) }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> تحديث الخادم</button>
    </form>
</div>
@endsection

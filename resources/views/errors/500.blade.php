@extends('layouts.public')
@section('title', 'خطأ في الخادم - 500')
@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 40px;">
        <i class="fas fa-triangle-exclamation" style="font-size: 72px; color: var(--warning); margin-bottom: 20px;"></i>
        <h1 style="font-size: 36px; font-weight: 900; color: var(--primary-dark);">500</h1>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">حدث خطأ داخلي بالنظام</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">نحن نعمل على إصلاح هذه المشكلة حالياً.</p>
        <a href="{{ url('/') }}" class="btn btn-primary"><i class="fas fa-rotate-right"></i> إعادة المحاولة</a>
    </div>
</div>
@endsection

@extends('layouts.public')
@section('title', 'انتهت الجلسة - 419')
@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 40px;">
        <i class="fas fa-clock-rotate-left" style="font-size: 72px; color: var(--primary); margin-bottom: 20px;"></i>
        <h1 style="font-size: 36px; font-weight: 900; color: var(--primary-dark);">419</h1>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">انتهت مدة الجلسة</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">يرجى تحديث الصفحة وإعادة تسجيل الدخول.</p>
        <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-right-to-bracket"></i> تسجيل الدخول</a>
    </div>
</div>
@endsection

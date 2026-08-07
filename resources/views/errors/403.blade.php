@extends('layouts.public')
@section('title', 'غير مصرح - 403')
@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 40px;">
        <i class="fas fa-user-lock" style="font-size: 72px; color: var(--danger); margin-bottom: 20px;"></i>
        <h1 style="font-size: 36px; font-weight: 900; color: var(--primary-dark);">403</h1>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">وصول غير مصرح به</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">ليس لديك الصلاحيات الكافية للوصول إلى هذه الصفحة.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fas fa-gauge-high"></i> الذهاب إلى لوحة التحكم</a>
    </div>
</div>
@endsection

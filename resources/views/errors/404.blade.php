@extends('layouts.public')
@section('title', 'الصفحة غير موجودة - 404')
@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 40px;">
        <i class="fas fa-compass-drafting" style="font-size: 72px; color: var(--accent); margin-bottom: 20px;"></i>
        <h1 style="font-size: 36px; font-weight: 900; color: var(--primary-dark);">404</h1>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">عفواً! الصفحة غير موجودة</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">الصفحة التي تبحث عنها غير موجودة أو تم نقلها إلى عنوان آخر.</p>
        <a href="{{ url('/') }}" class="btn btn-primary"><i class="fas fa-home"></i> العودة للرئيسية</a>
    </div>
</div>
@endsection

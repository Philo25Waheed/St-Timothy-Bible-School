@extends('layouts.app')
@section('title', 'نظام الأوسمة والإنجازات')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">نظام الأوسمة والألقاب الكنسية</h1>
        <p class="page-subtitle">شارات التميز والمواظبة والتفوق للطلاب</p>
    </div>
</div>

<div class="grid grid-cols-3">
    @foreach($achievements as $ach)
        <div class="card" style="text-align: center; padding: 30px;">
            <div style="width: 70px; height: 70px; background: #fef3c7; border: 2px solid #f59e0b; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; color: var(--accent); margin-bottom: 16px;">
                <i class="{{ $ach->icon }}"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: #92400e; margin-bottom: 8px;">{{ $ach->title }}</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">{{ $ach->description }}</p>
            <span class="badge badge-info">{{ $ach->students_count }} طلاب حصلوا عليه</span>
        </div>
    @endforeach
</div>
@endsection

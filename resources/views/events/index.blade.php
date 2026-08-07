@extends('layouts.app')
@section('title', 'التقويم والفعاليات')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">التقويم والفعاليات الكنسية</h1>
        <p class="page-subtitle">جدول الرحلات والامتحانات واجتماعات التربية الكنسية</p>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isServant())
        <a href="{{ route('events.create') }}" class="btn btn-primary"><i class="fas fa-calendar-plus"></i> إضافة فعالية جديدة</a>
    @endif
</div>

<div class="grid grid-cols-3">
    @forelse($events as $event)
        <div class="card">
            <span class="badge badge-warning" style="margin-bottom: 8px;">
                <i class="fas fa-tag"></i> {{ $event->event_type }}
            </span>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">{{ $event->title }}</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">{{ $event->description }}</p>
            <div style="font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                <div><i class="fas fa-clock" style="color: var(--primary-light);"></i> {{ $event->start_time ? $event->start_time->format('Y-m-d H:i') : '' }}</div>
                <div><i class="fas fa-location-dot" style="color: var(--danger);"></i> {{ $event->location ?: 'الكنيسة' }}</div>
            </div>
        </div>
    @empty
        <div style="grid-column: span 3; text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد فعاليات مسجلة.</div>
    @endforelse
</div>
@endsection

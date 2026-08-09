@extends('layouts.app')
@section('title', 'التقويم والفعاليات والرحلات')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-calendar-event-fill text-primary me-2"></i> التقويم والفعاليات والرحلات</h4>
        <p class="text-muted mb-0 small">جدول الرحلات، الكورسات الصيفية، واجتماعات التربية الكنسية مع إمكانية تأكيد المشاركة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('events.gallery') }}" class="btn btn-outline-primary rounded-3 fw-bold">
            <i class="bi bi-images me-1"></i> معرض الصور والألبوم
        </a>
        @if(Auth::user()->isAdmin() || Auth::user()->isServant())
            <a href="{{ route('events.create') }}" class="btn btn-primary rounded-3 fw-bold"><i class="bi bi-plus-lg me-1"></i> إضافة فعالية جديدة</a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    @forelse($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 border-top border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1">
                        <i class="bi bi-tag-fill me-1"></i> {{ $event->event_type }}
                    </span>
                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                        <i class="bi bi-people-fill me-1"></i> {{ $event->registrations->count() }} مشترك
                    </span>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ $event->title }}</h5>
                <p class="text-secondary small mb-3">{{ $event->description }}</p>
                
                <div class="bg-light p-2 rounded-3 small mb-3">
                    <div class="mb-1"><i class="bi bi-clock-history text-primary me-1"></i> <strong>الموعد:</strong> {{ $event->start_time ? $event->start_time->format('Y-m-d h:i A') : '' }}</div>
                    <div><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>المكان:</strong> {{ $event->location ?: 'الكنيسة' }}</div>
                </div>

                <div class="mt-auto">
                    @php $isRegistered = isset($userRegistrations[$event->id]); @endphp
                    @if($isRegistered)
                        <form action="{{ route('events.cancel_registration', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-3 btn-sm fw-bold">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> أنت مشترك بالفعالية (إلغاء)
                            </button>
                        </form>
                    @else
                        <form action="{{ route('events.register', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 rounded-3 btn-sm fw-bold">
                                <i class="bi bi-ticket-perforated-fill me-1"></i> تأكيد الحجز / المشاركة
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-calendar-x fs-1 text-muted"></i>
            <h5 class="fw-bold text-dark mt-2">لا توجد فعاليات أو رحلات مسجلة</h5>
            <p class="text-muted">ستظهر الرحلات والأنشطة القادمة هنا للراغبين في الحجز والمشاركة.</p>
        </div>
    @endforelse
</div>
@endsection

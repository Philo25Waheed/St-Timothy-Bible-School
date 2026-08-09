@extends('layouts.app')

@section('title', 'معرض الصور والذكريات')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-purple bg-opacity-10 p-3 text-purple d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; color: #6f42c1;">
                <i class="bi bi-images fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">معرض الصور والألبوم الكنسي (Photo Gallery)</h4>
                <p class="text-muted mb-0 small">ألبوم ذكريات الرحلات، الأنشطة الميدانية، والكورسات الصيفية بمدرسة الكتاب المقدس</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-calendar-event me-1"></i> جدول الأنشطة والرحلات
            </a>

            @if(Auth::user()->isServant() || Auth::user()->isAdmin())
                <button type="button" class="btn btn-primary rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                    <i class="bi bi-plus-lg me-1"></i> إضافة صورة جديدة المعرض
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Photo Gallery Grid -->
    <div class="row g-4">
        @forelse($photos as $photo)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 card-hover">
                    <div class="position-relative overflow-hidden" style="height: 220px; background: #f8f9fa;">
                        <img src="{{ $photo->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $photo->title }}" onerror="this.src='https://images.unsplash.com/photo-1511649475669-e288648b2339?w=600&auto=format&fit=crop'">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 rounded-pill px-3 py-2">
                            <i class="bi bi-calendar-event me-1"></i> {{ $photo->event->title ?? 'فعالية كنسية' }}
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-1">{{ $photo->title }}</h6>
                        <p class="text-muted small mb-0">{{ $photo->caption ?? 'لحظات مباركة من الأنشطة' }}</p>
                        <small class="text-muted d-block mt-2 fs-7"><i class="bi bi-clock me-1"></i> {{ $photo->created_at->format('Y-m-d') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-camera fs-1 text-muted"></i>
                    <h5 class="fw-bold text-dark mt-2">لا توجد صور في المعرض بعد</h5>
                    <p class="text-muted">قم بإضافة أول صور الأنشطة والرحلات لتظهر هنا كألبوم ذكريات جميل!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Upload Photo Modal -->
@if(Auth::user()->isServant() || Auth::user()->isAdmin())
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-image me-2"></i> إضافة صورة لمعرض الأنشطة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('events.photos.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اختر الفعالية / الرحلة المرتبطة</label>
                        <select name="event_id" class="form-select rounded-3" required>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->start_time->format('Y-m-d') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الصورة / اللقطة</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="مثال: لقطة تذكارية أمام الدير" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصورة (Image URL)</label>
                        <input type="url" name="image_url" class="form-control rounded-3" placeholder="https://..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تعليق / وصف قصير</label>
                        <input type="text" name="caption" class="form-control rounded-3" placeholder="مثال: فرحة الطلاب بعد الفوز بالمسابقة">
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold">إضافة لمعرض الصور</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

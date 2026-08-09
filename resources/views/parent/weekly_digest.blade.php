@extends('layouts.app')

@section('title', 'التقرير الأسبوعي لولي الأمر')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-4 bg-white p-1 border shadow-sm d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid rounded-3">
            </div>
            <div>
                <h4 class="fw-bold mb-1">التقرير الأسبوعي لولي الأمر (Weekly Progress Digest)</h4>
                <p class="text-muted mb-0 small">متابعة دقيقة لأداء وحضور وتحصيل الأبناء خلال الأسبوع الحالي ({{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }})</p>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-outline-dark rounded-3 fw-bold">
            <i class="bi bi-printer-fill me-1"></i> طباعة التقرير
        </button>
    </div>

    @forelse($childrenData as $child)
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <!-- Student Header Banner -->
            <div class="card-header bg-primary text-white p-4 border-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $child['user']->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($child['user']->name) }}" class="rounded-circle border border-3 border-white shadow-sm" width="64" height="64" alt="Student">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $child['user']->name }}</h4>
                            <p class="mb-0 text-white-50 small">
                                <i class="bi bi-mortarboard-fill me-1"></i> {{ $child['profile']->grade->name ?? 'الصف الدراسي' }} •
                                <i class="bi bi-people-fill me-1"></i> {{ $child['profile']->schoolClass->name ?? 'الفصل' }} •
                                <i class="bi bi-upc-scan me-1"></i> الكود: <strong>{{ $child['profile']->code }}</strong>
                            </p>
                        </div>
                    </div>

                    <a href="{{ $child['whatsapp_link'] }}" target="_blank" class="btn btn-success rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-whatsapp me-1"></i> التواصل مع الخادم على واتساب
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- KPI Stats Row -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-md-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded-4 border border-success border-opacity-25">
                            <i class="bi bi-calendar-check-fill text-success fs-3"></i>
                            <h3 class="fw-bold text-success mb-0 mt-1">{{ $child['present_days'] }}</h3>
                            <small class="text-muted fw-bold">أيام الحضور هذا الأسبوع</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-danger bg-opacity-10 rounded-4 border border-danger border-opacity-25">
                            <i class="bi bi-calendar-x-fill text-danger fs-3"></i>
                            <h3 class="fw-bold text-danger mb-0 mt-1">{{ $child['absent_days'] }}</h3>
                            <small class="text-muted fw-bold">أيام الغياب هذا الأسبوع</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-4 border border-warning border-opacity-25">
                            <i class="bi bi-star-fill text-warning fs-3"></i>
                            <h3 class="fw-bold text-dark mb-0 mt-1">+{{ $child['weekly_points'] }}</h3>
                            <small class="text-muted fw-bold">نقاط الأسبوع (الإجمالي: {{ $child['total_points'] }})</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25">
                            <i class="bi bi-book-half text-primary fs-3"></i>
                            <h3 class="fw-bold text-primary mb-0 mt-1">{{ $child['completed_verses']->count() }}</h3>
                            <small class="text-muted fw-bold">آيات تم حفظها بتمَيُّز</small>
                        </div>
                    </div>
                </div>

                <!-- Detailed Tables Row -->
                <div class="row g-4">
                    <!-- Column 1: Memory Verses Progress -->
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded-4 h-100 border">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-journal-check text-primary me-2"></i> آيات حفظ الكتاب المقدس</h6>
                            @forelse($child['completed_verses'] as $vp)
                                <div class="p-2 bg-white rounded-3 shadow-sm mb-2 border-start border-3 border-success">
                                    <small class="fw-bold text-success d-block">{{ $vp->verse->reference ?? 'آية مقدسة' }}</small>
                                    <p class="small text-dark mb-0">"{{ $vp->verse->text ?? '' }}"</p>
                                </div>
                            @empty
                                <p class="text-muted small">لم يتم تسجيل آيات جديدة هذا الأسبوع.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Column 2: Quizzes & Exam Scores -->
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded-4 h-100 border">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-award-fill text-warning me-2"></i> درجات الكويدزات والاختبارات</h6>
                            @forelse($child['recent_quizzes'] as $q)
                                <div class="p-2 bg-white rounded-3 shadow-sm mb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold small mb-0">{{ $q->quiz->title ?? 'اختبار أسبوعي' }}</h6>
                                        <small class="text-muted">{{ $q->created_at->format('Y-m-d') }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill fs-6">{{ $q->score }} / {{ $q->quiz->total_marks ?? 10 }}</span>
                                </div>
                            @empty
                                <p class="text-muted small">لا توجد اختبارات حديثة هذا الأسبوع.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-people fs-1 text-muted"></i>
            <h5 class="fw-bold text-dark mt-2">لم يتم ربط أبناء بحساب ولي الأمر بعد</h5>
            <p class="text-muted">تواصل مع إدارة المدرسة لربط أطفالك بحسابك لمتابعتهم.</p>
        </div>
    @endforelse
</div>
@endsection

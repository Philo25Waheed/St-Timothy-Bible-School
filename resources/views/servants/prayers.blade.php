@extends('layouts.app')

@section('title', 'متابعة طلبات صلوات الطلاب')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <i class="bi bi-hands-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">متابعة طلبات صلوات الطلاب (Pastoral Prayer Tracker)</h4>
                <p class="text-muted mb-0 small">الصلوات والطلبات السرية المرسلة من مخدوميك لمتابعتها ورعايتهم روحياً</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($prayers as $prayer)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-top border-4 @if($prayer->status=='answered') border-success @elseif($prayer->status=='praying') border-warning @else border-danger @endif">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $prayer->student->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($prayer->student->user->name) }}" class="rounded-circle" width="36" height="36" alt="student">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $prayer->student->user->name }}</h6>
                                        <small class="text-muted">{{ $prayer->student->schoolClass->name ?? 'فصل غير محدد' }}</small>
                                    </div>
                                </div>
                                <span class="badge @if($prayer->status=='answered') bg-success @elseif($prayer->status=='praying') bg-warning text-dark @else bg-danger @endif rounded-pill px-3 py-1">
                                    @if($prayer->status=='answered') تمت الاستجابة 🙌 @elseif($prayer->status=='praying') جاري الصلاة 🙏 @else جديدة ⏳ @endif
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">{{ $prayer->title }}</h5>
                            <p class="text-secondary small mb-3" style="white-space: pre-line;">{{ $prayer->details }}</p>

                            @if($prayer->servant_notes)
                                <div class="bg-light p-2 rounded-3 border mb-3">
                                    <small class="fw-bold text-primary"><i class="bi bi-chat-left-text me-1"></i> ردّك السابق:</small>
                                    <p class="small text-muted mb-0 mt-1">{{ $prayer->servant_notes }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Form -->
                        <form action="{{ route('servant.prayers.update', $prayer->id) }}" method="POST" class="mt-3 bg-light p-3 rounded-3 border border-light">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-bold">تحديث حالة الطلبة</label>
                                <select name="status" class="form-select form-select-sm rounded-2">
                                    <option value="pending" @selected($prayer->status=='pending')>قيد المتابعة ⏳</option>
                                    <option value="praying" @selected($prayer->status=='praying')>جاري الصلاة لأجله 🙏</option>
                                    <option value="answered" @selected($prayer->status=='answered')>تمت الاستجابة 🙌</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">رسالة تشجيع / آية للطالب</label>
                                <input type="text" name="servant_notes" class="form-control form-control-sm rounded-2" placeholder="أرسل كلمة تشجيع للطالب..." value="{{ $prayer->servant_notes }}">
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100 rounded-2 fw-bold">
                                <i class="bi bi-save me-1"></i> حفظ وتحديث
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-check-all fs-1 text-success"></i>
                    <h5 class="fw-bold text-dark mt-2">لا توجد طلبات صلاة جديدة حالياً</h5>
                    <p class="text-muted">ستظهر هنا طلبات الصلاة التي يرسلها طلاب فصلك لمتابعتها معهم.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

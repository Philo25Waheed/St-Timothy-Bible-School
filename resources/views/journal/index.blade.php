@extends('layouts.app')

@section('title', 'دفتر التخصيص والصلوات الشخصي')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <i class="bi bi-journal-bookmark-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">دفتر التخصيص والصلوات (Journal & Prayer Wall)</h4>
                <p class="text-muted mb-0 small">مساحتك الخاصة للتأملات والصلوات الفردية وطلب الصلوات من خادم فصلك</p>
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
        <!-- Left Side: Add Journal & Prayer Request Forms -->
        <div class="col-lg-5">
            <!-- Card 1: Add Journal Entry -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0 p-3 rounded-top-4">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-pencil-square me-2"></i> كِتابة تأمُّل جديد</h5>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('journal.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">عنوان التأمل / الآية</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="مثال: تأمل في مزمور 23" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">الحالة الروحية اليوم</label>
                            <select name="mood" class="form-select rounded-3">
                                <option value="مبتهج 😇">مبتهج 😇</option>
                                <option value="مصلّي 🙏">مصلّي 🙏</option>
                                <option value="سلام داخلي 🕊️">سلام داخلي 🕊️</option>
                                <option value="قارئ للكلمة 📖">قارئ للكلمة 📖</option>
                                <option value="يحتاج تشجيع 🤍">يحتاج تشجيع 🤍</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">محتوى التأمل أو مشاعرك مع الله</label>
                            <textarea name="content" rows="4" class="form-control rounded-3" placeholder="اكتب تأملك الشخصي هنا..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">
                            <i class="bi bi-bookmark-plus me-1"></i> حفظ التأمُّل في دفتري
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card 2: Send Confidential Prayer Request -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-danger bg-opacity-10 border-0 p-3 rounded-top-4">
                    <h5 class="fw-bold text-danger mb-0"><i class="bi bi-heart-pulse me-2"></i> إرسال طلبة صلاة لخادم الفصل</h5>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('prayers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">موضوع الصلاة</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="مثال: صلاة لأجل الامتحانات / صلاة لأجل شقيق مريض" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">تفاصيل طلبة الصلاة (سرية بينك وبين خادمك)</label>
                            <textarea name="details" rows="3" class="form-control rounded-3" placeholder="اكتب طلبك ليشترك معك خادمك في الصلاة..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 rounded-3 fw-bold">
                            <i class="bi bi-send-fill me-1"></i> إرسال الطلبة للخادم
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Journal Timeline & Prayer History -->
        <div class="col-lg-7">
            <!-- Nav Tabs -->
            <ul class="nav nav-pills bg-white p-2 rounded-4 shadow-sm mb-4 border border-light" id="journalTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 rounded-3 active fw-bold" id="journals-tab" data-bs-toggle="tab" data-bs-target="#journals-panel" type="button" role="tab">
                        <i class="bi bi-journal-text me-2"></i> دفتري الشخصي ({{ $journals->count() }})
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 rounded-3 fw-bold" id="prayers-tab" data-bs-toggle="tab" data-bs-target="#prayers-panel" type="button" role="tab">
                        <i class="bi bi-hands me-2"></i> طلبات صلواتي ({{ $prayers->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="journalTabContent">
                <!-- Journals Panel -->
                <div class="tab-pane fade show active" id="journals-panel" role="tabpanel">
                    @forelse($journals as $journal)
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0">{{ $journal->title }}</h6>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                                        {{ $journal->mood ?? 'تأمل' }} • {{ $journal->created_at->format('Y-m-d') }}
                                    </span>
                                </div>
                                <p class="text-secondary small mb-0" style="white-space: pre-line;">{{ $journal->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                            <i class="bi bi-journal-x fs-1 text-muted"></i>
                            <p class="text-muted mt-2">لا توجد تأملات مكتوبة بعد. ابدأ بكتابة تأملك الأول اليوم!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Prayers Panel -->
                <div class="tab-pane fade" id="prayers-panel" role="tabpanel">
                    @forelse($prayers as $prayer)
                        <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 @if($prayer->status=='answered') border-success @elseif($prayer->status=='praying') border-warning @else border-secondary @endif">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0">{{ $prayer->title }}</h6>
                                    @if($prayer->status == 'answered')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="bi bi-check-all me-1"></i> تمت الاستجابة 🙌</span>
                                    @elseif($prayer->status == 'praying')
                                        <span class="badge bg-warning bg-opacity-10 text-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hands me-1"></i> الخادم يصلي لأجلك 🙏</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2"><i class="bi bi-clock me-1"></i> قيد المتابعة</span>
                                    @endif
                                </div>
                                <p class="text-secondary small mb-2">{{ $prayer->details }}</p>

                                @if($prayer->servant_notes)
                                    <div class="bg-light p-2 rounded-3 border-start border-primary border-3 mt-2">
                                        <small class="fw-bold text-primary"><i class="bi bi-chat-quote-fill me-1"></i> رد وتدريب الخادم ({{ $prayer->servant->name ?? 'الخادم' }}):</small>
                                        <p class="small text-dark mb-0 mt-1">{{ $prayer->servant_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                            <i class="bi bi-heartbreak fs-1 text-muted"></i>
                            <p class="text-muted mt-2">لم تقم بإرسال طلبات صلاة بعد.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

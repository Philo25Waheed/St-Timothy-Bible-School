@extends('layouts.app')
@section('title', 'الرسائل والمحادثات المباشرة')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-chat-dots-fill text-primary me-2"></i> الرسائل والمحادثات المباشرة
            @if(isset($totalUnreadMessages) && $totalUnreadMessages > 0)
                <span class="badge bg-danger rounded-pill fs-6 ms-2">{{ $totalUnreadMessages }} غير مقروءة</span>
            @endif
        </h4>
        <p class="text-muted mb-0 small">تواصل آمن ومباشر ومصرح به بين الخدام والطلاب وأولياء الأمور وإدارة المدرسة</p>
    </div>
    
    <!-- New Chat Dropdown Modal -->
    <div class="dropdown">
        <button class="btn btn-primary rounded-3 fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-plus-lg me-1"></i> محادثة جديدة
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2" style="min-width: 260px; max-height: 320px; overflow-y: auto;">
            <li class="dropdown-header fw-bold text-muted">جهات الاتصال المتاحة بحسب صلاحيتك:</li>
            @forelse($availableContacts as $ac)
                <li>
                    <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('messages.index', ['user_id' => $ac->id]) }}">
                        <img src="{{ $ac->avatar_url }}" class="rounded-circle" width="32" height="32" alt="avatar">
                        <div>
                            <div class="fw-bold text-dark small">{{ $ac->name }}</div>
                            <small class="text-muted fs-7">
                                @switch($ac->role)
                                    @case('servant') خادم الفصل @break
                                    @case('student') طالب @break
                                    @case('parent') ولي أمر @break
                                    @default مسؤول النظام
                                @endswitch
                            </small>
                        </div>
                    </a>
                </li>
            @empty
                <li class="text-muted small text-center py-2">لا يوجد جهات اتصال متاحة حالياً</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="row g-4" style="min-height: 600px;">
    <!-- Conversations Sidebar (WhatsApp Style Sorting) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-chat-square-text-fill text-primary me-2"></i> المحادثات (الأحدث أولاً)
                </h6>
                <span class="badge bg-light text-muted border rounded-pill">{{ $conversations->count() }}</span>
            </div>

            <div class="d-flex flex-column gap-2 overflow-y-auto pe-1" style="max-height: 520px;">
                @forelse($conversations as $item)
                    @php 
                        $contact = $item['contact'];
                        $lastMsg = $item['last_message'];
                        $unread = $item['unread_count'];
                        $isActive = ($activeContact && $activeContact->id == $contact->id);
                    @endphp

                    <a href="{{ route('messages.index', ['user_id' => $contact->id]) }}" 
                       class="p-3 rounded-4 border text-decoration-none transition-all d-flex align-items-center justify-content-between gap-3 @if($isActive) bg-primary bg-opacity-10 border-primary @else bg-white border-light hover-bg-light @endif">
                        
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $contact->avatar_url }}" class="rounded-circle border border-2 border-white shadow-sm" width="46" height="46" alt="Avatar">
                                @if($unread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 140px;">{{ $contact->name }}</h6>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2 py-0 fs-7">
                                        @switch($contact->role)
                                            @case('servant') خادم @break
                                            @case('student') طالب @break
                                            @case('parent') ولي أمر @break
                                            @default أدمن
                                        @endswitch
                                    </span>
                                </div>
                                <p class="text-secondary small mb-0 text-truncate" style="max-width: 160px;">
                                    @if($lastMsg->sender_id == Auth::id())
                                        <span class="text-muted">أنت: </span>
                                    @endif
                                    {{ $lastMsg->message }}
                                </p>
                            </div>
                        </div>

                        <div class="text-end flex-shrink-0">
                            <small class="text-muted d-block fs-7 mb-1">{{ $lastMsg->created_at->diffForHumans(null, true, true) }}</small>
                            @if($unread > 0)
                                <span class="badge bg-danger rounded-pill px-2 py-1 fs-7 fw-bold">{{ $unread }} جديدة</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-square-dots fs-1 text-muted d-block mb-2"></i>
                        <p class="small mb-0">لا توجد محادثات سابقة.</p>
                        <small>اختر شخصًا من الزر بالأعلى لبدء أول محادثة!</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Chat Box Stream -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4 d-flex flex-column justify-content-between">
            @if($activeContact)
                <div>
                    <!-- Chat Active Header -->
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $activeContact->avatar_url }}" class="rounded-circle border border-2 border-primary" width="50" height="50" alt="Avatar">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $activeContact->name }}</h5>
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i>
                                    @switch($activeContact->role)
                                        @case('servant') خادم الفصل @break
                                        @case('student') طالب بالمدرسة @break
                                        @case('parent') ولي أمر @break
                                        @default مسؤول النظام (Admin)
                                    @endswitch
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Stream Container -->
                    <div class="d-flex flex-column gap-3 p-2 overflow-y-auto" style="max-height: 400px; min-height: 340px;">
                        @forelse($messages as $msg)
                            @php $isMe = ($msg->sender_id == Auth::id()); @endphp
                            <div class="d-flex flex-column align-items-{{ $isMe ? 'end' : 'start' }}">
                                <div class="p-3 rounded-4 max-w-75 shadow-sm @if($isMe) bg-primary text-white rounded-bottom-end-0 @else bg-light text-dark border rounded-bottom-start-0 @endif" style="max-width: 75%;">
                                    <div class="mb-1" style="white-space: pre-line;">{{ $msg->message }}</div>
                                    <div class="d-flex align-items-center justify-content-end gap-1 opacity-75 fs-7">
                                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                                        @if($isMe)
                                            <i class="bi bi-check2-all @if($msg->is_read) text-warning @endif"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted my-auto">
                                <i class="bi bi-chat-quote fs-1 text-primary mb-2 d-block opacity-50"></i>
                                <h6 class="fw-bold">ابدأ المحادثة الآن مع {{ $activeContact->name }}</h6>
                                <small>أرسل أول رسالة مباشرة ومحمية عبر المنصة.</small>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Send Message Input Form -->
                <form action="{{ route('messages.store') }}" method="POST" class="border-top pt-3 mt-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                    <div class="input-group">
                        <input type="text" name="message" class="form-control form-control-lg rounded-start-4 rounded-0 border-end-0" placeholder="اكتب رسالتك لـ {{ $activeContact->name }}..." required autofocus>
                        <button type="submit" class="btn btn-primary btn-lg rounded-end-4 px-4 fw-bold">
                            <i class="bi bi-send-fill me-1"></i> إرسال
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-5 text-muted my-auto">
                    <i class="bi bi-chat-square-text fs-1 text-secondary mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">حدد محادثة من القائمة أو ابدأ محادثة جديدة</h5>
                    <p class="small text-muted">يمكنك التواصل المباشر بأمان مع الخدمة والطلاب والمسؤولين.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

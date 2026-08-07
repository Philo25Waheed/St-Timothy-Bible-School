@extends('layouts.app')
@section('title', 'الرسائل المباشرة')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">الرسائل والتواصل المباشر</h1>
        <p class="page-subtitle">تواصل آمن ومباشر بين الخدام والطلاب وأولياء الأمور والإدارة</p>
    </div>
</div>

<div class="grid grid-cols-3" style="min-height: 550px;">
    <!-- Conversations Sidebar -->
    <div class="card" style="padding: 16px;">
        <h3 style="font-size: 15px; font-weight: 800; margin-bottom: 14px;"><i class="fas fa-comments" style="color: var(--primary);"></i> المحادثات</h3>
        
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @forelse($availableContacts as $contact)
                <a href="{{ route('messages.index', ['user_id' => $contact->id]) }}" 
                   style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: {{ $activeContact && $activeContact->id == $contact->id ? '#eff6ff' : '#ffffff' }};">
                    <img src="{{ $contact->avatar_url }}" style="width: 40px; height: 40px; border-radius: 50%;">
                    <div>
                        <div style="font-weight: 700; font-size: 14px; color: var(--primary-dark);">{{ $contact->name }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">
                            @switch($contact->role)
                                @case('servant') خادم الفصل @break
                                @case('parent') ولي أمر @break
                                @case('student') طالب (مخدوم) @break
                                @default مسؤول النظام
                            @endswitch
                        </div>
                    </div>
                </a>
            @empty
                <div style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px;">لا يوجد جهات تواصل متاحة.</div>
            @endforelse
        </div>
    </div>

    <!-- Active Chat Box -->
    <div class="card" style="grid-column: span 2; display: flex; flex-direction: column; justify-content: space-between; padding: 24px;">
        @if($activeContact)
            <div>
                <!-- Chat Header -->
                <div style="display: flex; align-items: center; gap: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px; margin-bottom: 20px;">
                    <img src="{{ $activeContact->avatar_url }}" style="width: 46px; height: 46px; border-radius: 50%;">
                    <div>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark);">{{ $activeContact->name }}</h3>
                        <div style="font-size: 12px; color: var(--text-muted);">محادثة مباشرة ومحمية في مدرسة الكتاب المقدس</div>
                    </div>
                </div>

                <!-- Messages Stream -->
                <div style="max-height: 350px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; padding-left: 10px;">
                    @forelse($messages as $msg)
                        <div style="max-width: 75%; padding: 12px 16px; border-radius: 16px; font-size: 14px; align-self: {{ $msg->sender_id == Auth::id() ? 'flex-end' : 'flex-start' }}; background: {{ $msg->sender_id == Auth::id() ? 'linear-gradient(135deg, #1e3a8a, #0f172a)' : '#f1f5f9' }}; color: {{ $msg->sender_id == Auth::id() ? '#ffffff' : 'var(--text-main)' }};">
                            <div>{{ $msg->message }}</div>
                            <div style="font-size: 10px; margin-top: 4px; text-align: left; opacity: 0.8;">
                                {{ $msg->created_at->format('H:i | Y-m-d') }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد رسائل سابقة. ابدأ المحادثة الآن.</div>
                    @endforelse
                </div>
            </div>

            <!-- Send Message Form -->
            <form action="{{ route('messages.store') }}" method="POST" style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="message" class="form-control" placeholder="اكتب رسالتك هنا..." required autofocus>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;"><i class="fas fa-paper-plane"></i> إرسال</button>
                </div>
            </form>
        @else
            <div style="text-align: center; color: var(--text-muted); margin: auto;">
                <i class="fas fa-comments" style="font-size: 64px; color: var(--text-light); margin-bottom: 16px;"></i>
                <h3>اختر شخصاً من القائمة الجانبية لبدء المحادثة</h3>
            </div>
        @endif
    </div>
</div>
@endsection

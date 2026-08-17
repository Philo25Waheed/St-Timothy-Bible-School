@extends('layouts.public')
@section('title', "مدرسة القديس تيموثاوس للكتاب المقدس")

@section('content')
<div class="landing-wrapper">
    <!-- Header Navbar -->
    <header class="landing-header">
        <div class="landing-brand">
            <div class="landing-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div>
                <div class="landing-brand-title">مدرسة القديس تيموثاوس للكتاب المقدس</div>
                <div class="landing-brand-subtitle">كنيسة السيدة العذراء والأنبا رويس - حدائق الأهرام</div>
            </div>
        </div>

        <div class="landing-nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="landing-nav-register"><i class="fas fa-gauge-high"></i> لوحة التحكم</a>
            @else
                <a href="{{ route('login') }}" class="landing-nav-login"><i class="fas fa-right-to-bracket"></i> تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="landing-nav-register"><i class="fas fa-user-plus"></i> إنشاء حساب جديد</a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <section class="landing-hero">
        <div class="landing-hero-container">
            <div style="margin-bottom: 24px;">
                <div class="landing-hero-logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>
            </div>

            <div class="landing-hero-badge">
                ✨ إيمان + تعليم + مجتمع + تقدم
            </div>
            <h1 class="landing-hero-title">
                منصة مدرسة القديس تيموثاوس للكتاب<br>المقدس
            </h1>
            <p class="landing-hero-desc">
                نظام تعليمي وتربوي متكامل يربط بين الطلاب، الخدام، وأولياء الأمور لتوفير تجربة تعلم تفاعلية ومحفزة في المناهج الكنسية وآيات الكتاب المقدس.
            </p>
            <div class="landing-hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="landing-hero-btn-register">
                        <i class="fas fa-gauge-high"></i> الانتقال إلى لوحة التحكم
                    </a>
                @else
                    <a href="{{ route('login') }}" class="landing-hero-btn-login">
                        <i class="fas fa-right-to-bracket"></i> تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}" class="landing-hero-btn-register">
                        <i class="fas fa-user-plus"></i> إنشاء حساب جديد
                    </a>
                @endauth
            </div>
        </div>
    </section>
</div>

<!-- Stats Banner -->
<section class="landing-stats-section">
    <div class="landing-stats-grid">
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--primary-dark);">{{ $stats['students'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700; margin-top: 4px;">طالب متميز</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--accent);">{{ $stats['servants'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700; margin-top: 4px;">خادم متخصص</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--success);">{{ $stats['lessons'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700; margin-top: 4px;">درس تفاعلي</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: #8b5cf6;">{{ $stats['events'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700; margin-top: 4px;">فعالية ونشاط</div>
        </div>
    </div>
</section>

<!-- Educational Stages -->
<section id="about" style="padding: 70px 20px; max-width: 1200px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 44px;">
        <h2 style="font-size: 28px; font-weight: 900; color: var(--primary-dark);">المراحل الدراسية والتعليمية</h2>
        <p style="color: var(--text-muted); font-size: 15px; margin-top: 8px;">مناهج مخصصة لكل مرحلة عمرية تناسب النمو الروحي والفكري</p>
    </div>

    <div class="grid grid-cols-3">
        @foreach($stages as $stage)
            <div class="card" style="text-align: center; padding: 32px 20px;">
                <div style="width: 54px; height: 54px; background: #eff6ff; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; color: var(--primary); margin-bottom: 16px;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 10px;">{{ $stage->name }}</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">{{ $stage->description }}</p>
                <span class="badge badge-info" style="font-size: 12px;">{{ $stage->students_count }} طالب مسجل</span>
            </div>
        @endforeach
    </div>
</section>

<!-- Footer -->
<footer style="background: var(--primary-dark); color: #94a3b8; padding: 36px 20px; text-align: center; border-top: 1px solid rgba(255,255,255,0.1);">
    <div style="font-size: 16px; font-weight: 800; color: white; margin-bottom: 8px;">مدرسة القديس تيموثاوس لدراسة الكتاب المقدس</div>
    <div style="font-size: 13px;">جميع الحقوق محفوظة © {{ date('Y') }} - مدرسة القديس تيموثاوس لدراسة الكتاب المقدس</div>
</footer>
@endsection

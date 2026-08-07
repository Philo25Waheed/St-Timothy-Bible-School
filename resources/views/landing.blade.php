@extends('layouts.public')
@section('title', 'مدرسة الكتاب المقدس - المنصة التعليمية الرقمية')

@section('content')
<!-- Header Navbar -->
<header style="background: var(--primary-dark); color: white; padding: 18px 40px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
    <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--accent-light), var(--accent)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: white;">
            <i class="fas fa-bible"></i>
        </div>
        <div>
            <div style="font-size: 20px; font-weight: 800; color: white;">مدرسة الكتاب المقدس</div>
            <div style="font-size: 11px; color: var(--accent-gold);">منصة التعليم والتربية الكنسية المبتكرة</div>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-accent"><i class="fas fa-gauge-high"></i> لوحة التحكم</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline" style="color: white; border-color: rgba(255,255,255,0.4); padding: 10px 20px; font-size: 15px;"><i class="fas fa-right-to-bracket"></i> تسجيل الدخول</a>
            <a href="{{ route('register') }}" class="btn btn-accent" style="padding: 10px 22px; font-size: 15px;"><i class="fas fa-user-plus"></i> إنشاء حساب جديد</a>
        @endauth
    </div>
</header>

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: white; padding: 100px 40px 120px; text-align: center; position: relative; overflow: hidden;">
    <div style="max-width: 900px; margin: 0 auto; position: relative; z-index: 2;">
        <span style="background: rgba(217, 119, 6, 0.2); color: var(--accent-gold); border: 1px solid var(--accent); padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 800; display: inline-block; margin-bottom: 20px;">
            ✨ إيمان + تعليم + مجتمع + تقدم
        </span>
        <h1 style="font-size: 48px; font-weight: 900; line-height: 1.2; margin-bottom: 20px; letter-spacing: -0.5px;">
            منصة مدرسة الكتاب المقدس والتربية الكنسية
        </h1>
        <p style="font-size: 18px; color: #94a3b8; margin-bottom: 36px; line-height: 1.8;">
            نظام تعليمي وتربوي متكامل يربط بين الطلاب، الخدام، المسؤولين، وأولياء الأمور لتوفير تجربة تعلم تفاعلية ومحفزة في المناهج الكنسية وآيات الكتاب المقدس.
        </p>
        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('register') }}" class="btn btn-accent" style="padding: 14px 32px; font-size: 16px; border-radius: 30px;">
                <i class="fas fa-user-plus"></i> إنشاء حساب جديد
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline" style="color: white; border-color: rgba(255,255,255,0.4); padding: 14px 32px; font-size: 16px; border-radius: 30px;">
                <i class="fas fa-right-to-bracket"></i> تسجيل الدخول
            </a>
        </div>
    </div>
</section>

<!-- Stats Banner -->
<section style="margin-top: -50px; position: relative; z-index: 10; padding: 0 40px;">
    <div style="max-width: 1100px; margin: 0 auto; background: white; border-radius: var(--radius-lg); padding: 30px; box-shadow: var(--shadow-lg); display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center;">
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--primary-dark);">{{ $stats['students'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700;">طالب متميز</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--accent);">{{ $stats['servants'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700;">خادم متخصص</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: var(--success);">{{ $stats['lessons'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700;">درس تفاعلي</div>
        </div>
        <div>
            <div style="font-size: 32px; font-weight: 900; color: #8b5cf6;">{{ $stats['events'] }}</div>
            <div style="font-size: 14px; color: var(--text-muted); font-weight: 700;">فعالية ونشاط</div>
        </div>
    </div>
</section>

<!-- Educational Stages -->
<section id="about" style="padding: 80px 40px; max-width: 1200px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 32px; font-weight: 900; color: var(--primary-dark);">المراحل الدراسية والتعليمية</h2>
        <p style="color: var(--text-muted); font-size: 15px; margin-top: 8px;">مناهج مخصصة لكل مرحلة عمرية تناسب النمو الروحي والفكري</p>
    </div>

    <div class="grid grid-cols-3">
        @foreach($stages as $stage)
            <div class="card" style="text-align: center; padding: 36px 24px;">
                <div style="width: 60px; height: 60px; background: #eff6ff; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; color: var(--primary); margin-bottom: 20px;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 10px;">{{ $stage->name }}</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">{{ $stage->description }}</p>
                <span class="badge badge-info" style="font-size: 13px;">{{ $stage->students_count }} طالب مسجل</span>
            </div>
        @endforeach
    </div>
</section>

<!-- Footer -->
<footer style="background: var(--primary-dark); color: #94a3b8; padding: 40px; text-align: center; border-top: 1px solid rgba(255,255,255,0.1);">
    <div style="font-size: 18px; font-weight: 800; color: white; margin-bottom: 8px;">مدرسة الكتاب المقدس</div>
    <div style="font-size: 13px; margin-bottom: 20px;">جميع الحقوق محفوظة © {{ date('Y') }} - مدرسة الكتاب المقدس</div>
</footer>
@endsection

@extends('layouts.public')
@section('title', 'تسجيل الدخول')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); padding: 20px;">
    <div style="width: 100%; max-width: 480px;">
        <div class="card" style="padding: 40px; border-radius: var(--radius-lg); box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--accent-light), var(--accent)); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; color: white; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(217,119,6,0.3);">
                    <i class="fas fa-bible"></i>
                </div>
                <h1 style="font-size: 24px; font-weight: 800; color: var(--primary-dark);">مدرسة الكتاب المقدس</h1>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">سجّل دخولك للوصول إلى المنصة التعليمية</p>
            </div>

            @if($errors->any())
                <div style="background: #fee2e2; border-right: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                    <i class="fas fa-circle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@domain.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                        <input type="checkbox" name="remember"> تذكرني
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 16px;">
                    <i class="fas fa-right-to-bracket"></i> تسجيل الدخول
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted);">
                <a href="{{ url('/') }}" style="color: var(--primary-light);"><i class="fas fa-arrow-right"></i> العودة للموقع الرئيسي</a>
            </div>
        </div>
    </div>
</div>
@endsection

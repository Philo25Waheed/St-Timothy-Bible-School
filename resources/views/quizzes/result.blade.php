@extends('layouts.app')
@section('title', 'نتيجة الاختبار')

@section('content')
<div class="card" style="max-width: 600px; margin: 40px auto; text-align: center; padding: 40px;">
    @if($attempt->passed)
        <div style="width: 80px; height: 80px; background: #d1fae5; color: #10b981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">
            <i class="fas fa-trophy"></i>
        </div>
        <h1 style="font-size: 26px; font-weight: 900; color: var(--primary-dark);">مبروك! تم اجتياز الاختبار بنجاح 🎉</h1>
    @else
        <div style="width: 80px; height: 80px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">
            <i class="fas fa-rotate-right"></i>
        </div>
        <h1 style="font-size: 26px; font-weight: 900; color: var(--primary-dark);">حاول مرة أخرى! لم تجتاز الاختبار</h1>
    @endif

    <div style="font-size: 48px; font-weight: 900; color: var(--primary); margin: 20px 0;">
        {{ $attempt->percentage }}%
    </div>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 30px;">
        حصلت على {{ $attempt->score }} من إجمالي {{ $attempt->total_marks }} درجة.
    </p>

    <div style="display: flex; gap: 12px; justify-content: center;">
        <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fas fa-home"></i> العودة للوحة التحكم</a>
        <a href="{{ route('quizzes.take', $attempt->quiz_id) }}" class="btn btn-outline"><i class="fas fa-rotate-right"></i> إعادة الاختبار</a>
    </div>
</div>
@endsection

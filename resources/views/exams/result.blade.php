@extends('layouts.app')
@section('title', 'نتيجة الامتحان')

@section('content')
<div class="card" style="max-width: 600px; margin: 40px auto; text-align: center; padding: 40px;">
    @if($attempt->passed)
        <div style="width: 80px; height: 80px; background: #d1fae5; color: #10b981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">
            <i class="fas fa-award"></i>
        </div>
        <h1 style="font-size: 26px; font-weight: 900; color: var(--primary-dark);">نتيجة الامتحان الرسمي: ناجح 🎉</h1>
    @else
        <div style="width: 80px; height: 80px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h1 style="font-size: 26px; font-weight: 900; color: var(--primary-dark);">نتيجة الامتحان الرسمي: راسب</h1>
    @endif

    <div style="font-size: 48px; font-weight: 900; color: var(--primary); margin: 20px 0;">
        {{ $attempt->percentage }}%
    </div>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 30px;">
        حصلت على {{ $attempt->score }} من إجمالي {{ $attempt->total_marks }} درجة.
    </p>

    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fas fa-home"></i> العودة للوحة التحكم</a>
</div>
@endsection

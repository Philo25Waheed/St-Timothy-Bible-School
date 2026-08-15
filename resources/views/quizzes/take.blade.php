@extends('layouts.app')
@section('title', 'تقديم الاختبار: ' . $quiz->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $quiz->title }}</h1>
        <p class="page-subtitle">
            الفصل: <strong>{{ $quiz->schoolClass ? $quiz->schoolClass->name : 'عام' }}</strong> | 
            المدة: <strong>{{ $quiz->duration_minutes }} دقيقة</strong> | 
            الدرجة الكلية: <strong>{{ $quiz->total_marks }}</strong>
        </p>
    </div>
    <a href="{{ route('quizzes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> إلغاء والعودة</a>
</div>

@if($previousAttempt)
    <div class="card" style="background: #fef3c7; border-color: #f59e0b; padding: 16px 20px; margin-bottom: 24px;">
        <i class="fas fa-circle-info" style="color: #92400e;"></i> لقد قمت بتقديم هذا الاختبار سابقاً وحصلت على <strong>{{ $previousAttempt->percentage }}%</strong>. يمكنك تقديم محاولة جديدة أدناه.
    </div>
@endif

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
        @csrf
        @foreach($quiz->questions as $index => $q)
            <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 20px; border-radius: var(--radius-md); margin-bottom: 20px;">
                <div style="font-weight: 800; font-size: 16px; color: var(--primary-dark); margin-bottom: 14px; line-height: 1.4;">
                    السؤال {{ $index + 1 }} ({{ $q->marks }} درجات): {{ $q->question_text }}
                </div>

                @if($q->question_type === 'multiple_choice' || $q->question_type === 'true_false')
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($q->options ?? [] as $opt)
                            <label style="display: flex; align-items: center; gap: 12px; font-size: 15px; background: white; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); cursor: pointer; transition: background 0.2s ease;">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" required style="width: 18px; height: 18px;">
                                <span>{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <input type="text" name="answers[{{ $q->id }}]" class="form-control form-control-lg" placeholder="اكتب إجابتك هنا..." required>
                @endif
            </div>
        @endforeach

        <button type="submit" class="btn btn-accent btn-lg" style="width: 100%; justify-content: center; padding: 14px; font-size: 16px; font-weight: 800;">
            <i class="fas fa-paper-plane"></i> تسليم الاختبار وحساب النتيجة
        </button>
    </form>
</div>
@endsection

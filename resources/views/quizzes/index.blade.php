@extends('layouts.app')
@section('title', 'الاختبارات القصيرة')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">الاختبارات القصيرة (Quizzes)</h1>
        <p class="page-subtitle">قياس واستعراض مستوى الطلاب في الدروس الكنسية</p>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isServant())
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء اختبار جديد</a>
    @endif
</div>

<div class="grid grid-cols-3">
    @forelse($quizzes as $quiz)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <span class="badge badge-info" style="margin-bottom: 8px;">الدرس: {{ $quiz->lesson->title ?? 'عام' }}</span>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">{{ $quiz->title }}</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 14px;">{{ $quiz->description ?: 'اختبار لقياس الفهم والاستيعاب' }}</p>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">
                    <i class="fas fa-clock"></i> المدة: {{ $quiz->duration_minutes }} دقيقة | 
                    <i class="fas fa-question-circle"></i> {{ $quiz->questions->count() }} أسئلة | 
                    <i class="fas fa-check-double"></i> درجة النجاح: {{ $quiz->passing_score }}%
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                @if(Auth::user()->isStudent())
                    <a href="{{ route('quizzes.take', $quiz->id) }}" class="btn btn-accent btn-sm" style="flex: 1; justify-content: center;"><i class="fas fa-play"></i> تقديم الاختبار</a>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->isServant())
                    <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-outline btn-sm" style="flex: 1; justify-content: center;"><i class="fas fa-sliders"></i> منشئ الأسئلة</a>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد اختبارات قصيرة متوفرة حالياً.</div>
    @endforelse
</div>
@endsection

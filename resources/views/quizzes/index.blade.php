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
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    @if($quiz->schoolClass)
                        <span class="badge badge-warning"><i class="fas fa-school"></i> {{ $quiz->schoolClass->name }} ({{ $quiz->schoolClass->grade->name ?? '' }})</span>
                    @else
                        <span class="badge badge-info"><i class="fas fa-globe"></i> متاح لجميع الفصول</span>
                    @endif
                    @if($quiz->lesson)
                        <span class="badge badge-success"><i class="fas fa-book"></i> {{ Str::limit($quiz->lesson->title, 25) }}</span>
                    @endif
                </div>

                <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">{{ $quiz->title }}</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 14px;">{{ $quiz->description ?: 'اختبار لقياس الفهم والاستيعاب' }}</p>
                
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 8px;">
                    <span><i class="fas fa-clock text-warning"></i> {{ $quiz->duration_minutes }} دقيقة</span>
                    <span>•</span>
                    <span><i class="fas fa-question-circle text-primary"></i> {{ $quiz->questions->count() }} أسئلة</span>
                    <span>•</span>
                    <span><i class="fas fa-check-double text-success"></i> النجاح: {{ $quiz->passing_score }}%</span>
                </div>
            </div>

            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @if(Auth::user()->isStudent())
                    <a href="{{ route('quizzes.take', $quiz->id) }}" class="btn btn-accent btn-sm" style="flex: 1; justify-content: center;"><i class="fas fa-play"></i> تقديم الاختبار</a>
                @endif
                @if(Auth::user()->isAdmin() || (Auth::user()->isServant() && ($quiz->created_by === Auth::id() || (Auth::user()->assignedClasses && Auth::user()->assignedClasses->contains('id', $quiz->class_id)))))
                    <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-outline btn-sm" style="flex: 1; justify-content: center;"><i class="fas fa-sliders"></i> منشئ الأسئلة</a>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
            <i class="fas fa-tasks fa-3x mb-3 text-muted"></i>
            <p>لا يوجد اختبارات قصيرة متوفرة لفصلك حالياً.</p>
        </div>
    @endforelse
</div>
@endsection

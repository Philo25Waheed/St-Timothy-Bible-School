@extends('layouts.app')
@section('title', 'الامتحانات الرسمية')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">الامتحانات الرسمية (Exams)</h1>
        <p class="page-subtitle">الامتحانات النصفية والنهائية لمدرسة الكتاب المقدس</p>
    </div>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('exams.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء امتحان جديد</a>
    @endif
</div>

<div class="grid grid-cols-2">
    @forelse($exams as $exam)
        <div class="card">
            <span class="badge badge-info" style="margin-bottom: 8px;">{{ $exam->stage->name ?? '' }} - {{ $exam->grade->name ?? '' }}</span>
            <h3 style="font-size: 20px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">{{ $exam->title }}</h3>
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                <i class="fas fa-clock"></i> المدة: {{ $exam->duration_minutes }} دقيقة | 
                <i class="fas fa-question"></i> الأسئلة: {{ $exam->questions->count() }} أسئلة | 
                <i class="fas fa-award"></i> الدرجة الكلية: {{ $exam->total_marks }}
            </div>
            <div style="display: flex; gap: 8px;">
                @if(Auth::user()->isStudent())
                    <a href="{{ route('exams.take', $exam->id) }}" class="btn btn-accent"><i class="fas fa-file-signature"></i> بدء الامتحان</a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-outline"><i class="fas fa-sliders"></i> منشئ الأسئلة</a>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: span 2; text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد امتحانات رسمية حالياً.</div>
    @endforelse
</div>
@endsection

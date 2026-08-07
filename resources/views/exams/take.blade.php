@extends('layouts.app')
@section('title', 'تقديم الامتحان: ' . $exam->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $exam->title }}</h1>
        <p class="page-subtitle">المدة الرسمية: {{ $exam->duration_minutes }} دقيقة | الدرجة: {{ $exam->total_marks }}</p>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('exams.submit', $exam->id) }}" method="POST">
        @csrf
        @foreach($exam->questions as $index => $q)
            <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 20px; border-radius: var(--radius-md); margin-bottom: 20px;">
                <div style="font-weight: 800; font-size: 16px; color: var(--primary-dark); margin-bottom: 14px;">
                    سؤال {{ $index + 1 }}: {{ $q->question_text }}
                </div>

                @if($q->question_type === 'multiple_choice' || $q->question_type === 'true_false')
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($q->options ?? [] as $opt)
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; background: white; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); cursor: pointer;">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" required>
                                {{ $opt }}
                            </label>
                        @endforeach
                    </div>
                @else
                    <input type="text" name="answers[{{ $q->id }}]" class="form-control" placeholder="إجابتك..." required>
                @endif
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 16px;">
            <i class="fas fa-paper-plane"></i> إرسال وثيقة الامتحان
        </button>
    </form>
</div>
@endsection

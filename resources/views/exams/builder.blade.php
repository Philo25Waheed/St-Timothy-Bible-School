@extends('layouts.app')
@section('title', 'منشئ أسئلة الامتحان - ' . $exam->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">منشئ أسئلة الامتحان: {{ $exam->title }}</h1>
        <p class="page-subtitle">إجمالي الدرجات: <strong>{{ $exam->total_marks }}</strong> | عدد الأسئلة: <strong>{{ $exam->questions->count() }}</strong></p>
    </div>
    <a href="{{ route('exams.index') }}" class="btn btn-outline"><i class="fas fa-check"></i> إنهاء وحفظ</a>
</div>

<div class="grid grid-cols-3">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة سؤال امتحان</h3>
        <form action="{{ route('exams.questions.store', $exam->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">نص السؤال</label>
                <textarea name="question_text" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">نوع السؤال</label>
                <select name="question_type" class="form-control" required>
                    <option value="multiple_choice">اختيار من متعدد</option>
                    <option value="true_false">صواب أو خطأ</option>
                    <option value="short_answer">إجابة قصيرة</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الخيارات (مفصولة بفواصل ,)</label>
                <input type="text" name="options" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">الإجابة الصحيحة</label>
                <input type="text" name="correct_answer" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">درجة السؤال</label>
                <input type="number" name="marks" class="form-control" value="25" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;"><i class="fas fa-plus"></i> إضافة سؤال للامتحان</button>
        </form>
    </div>

    <div style="grid-column: span 2;">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-list-check"></i> أسئلة الامتحان</h3>
            @forelse($exam->questions as $index => $q)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-sm); margin-bottom: 12px;">
                    <div style="font-weight: 800; font-size: 15px; color: var(--primary-dark);">سؤال {{ $index + 1 }}: {{ $q->question_text }} ({{ $q->marks }} درجة)</div>
                    <div style="font-size: 12px; color: var(--success); font-weight: 700; margin-top: 4px;">✔ الإجابة الصحيحة: {{ $q->correct_answer }}</div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد أسئلة مضافة بعد.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

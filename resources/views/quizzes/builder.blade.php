@extends('layouts.app')
@section('title', 'منشئ الأسئلة - ' . $quiz->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">منشئ أسئلة الاختبار: {{ $quiz->title }}</h1>
        <p class="page-subtitle">إجمالي الدرجات: <strong>{{ $quiz->total_marks }}</strong> | عدد الأسئلة: <strong>{{ $quiz->questions->count() }}</strong></p>
    </div>
    <a href="{{ route('quizzes.index') }}" class="btn btn-outline"><i class="fas fa-check"></i> إنهاء وحفظ</a>
</div>

<div class="grid grid-cols-3">
    <!-- Form to Add Question -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة سؤال جديد</h3>
        <form action="{{ route('quizzes.questions.store', $quiz->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">نص السؤال</label>
                <textarea name="question_text" class="form-control" rows="3" required placeholder="أدخل نص السؤال هنا..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">نوع السؤال</label>
                <select name="question_type" class="form-control" required>
                    <option value="multiple_choice">اختيار من متعدد (Multiple Choice)</option>
                    <option value="true_false">صواب أو خطأ (True / False)</option>
                    <option value="short_answer">إجابة قصيرة (Short Answer)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الخيارات المتاحة (مفصولة بفواصل ,)</label>
                <input type="text" name="options" class="form-control" placeholder="خيار 1, خيار 2, خيار 3, خيار 4">
            </div>
            <div class="form-group">
                <label class="form-label">الإجابة الصحيحة بالضبط</label>
                <input type="text" name="correct_answer" class="form-control" required placeholder="اكتب الإجابة الصحيحة">
            </div>
            <div class="form-group">
                <label class="form-label">درجة السؤال</label>
                <input type="number" name="marks" class="form-control" value="10" required>
            </div>
            <div class="form-group">
                <label class="form-label">تفسير الإجابة (اختياري)</label>
                <input type="text" name="explanation" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;"><i class="fas fa-plus"></i> إضافة السؤال للاختبار</button>
        </form>
    </div>

    <!-- Questions List -->
    <div style="grid-column: span 2;">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-list-check"></i> أسئلة الاختبار الحالية</h3>
            @forelse($quiz->questions as $index => $q)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-sm); margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span class="badge badge-info" style="margin-bottom: 6px;">سؤال {{ $index + 1 }} ({{ $q->marks }} درجات)</span>
                            <div style="font-weight: 800; font-size: 15px; color: var(--primary-dark);">{{ $q->question_text }}</div>
                            @if($q->options)
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">الخيارات: {{ implode(' | ', $q->options) }}</div>
                            @endif
                            <div style="font-size: 12px; color: var(--success); font-weight: 700; margin-top: 4px;">✔ الإجابة الصحيحة: {{ $q->correct_answer }}</div>
                        </div>
                        <form action="{{ route('questions.destroy', $q->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 40px;">لم يتم إضافة أسئلة بعد. أضف أسئلة باستخدام النموذج الجانبي.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

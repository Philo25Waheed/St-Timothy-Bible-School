@extends('layouts.app')
@section('title', 'إنشاء اختبار جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إنشاء اختبار قصير جديد للفصل</h1>
        <p class="page-subtitle">تحديد الفصل والدرس الموجه لهما الاختبار</p>
    </div>
    <a href="{{ route('quizzes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة</a>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <form action="{{ route('quizzes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">عنوان الاختبار</label>
            <input type="text" name="title" class="form-control" placeholder="اختبار قصير: دراسات سفر التكوين" required>
        </div>
        
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">توجيه الاختبار للفصل الدراسي</label>
                <select name="class_id" class="form-control">
                    <option value="">جميع الفصول (عام)</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">الدرس المرتبط (اختياري)</label>
                <select name="lesson_id" class="form-control">
                    <option value="">اختبار عام</option>
                    @foreach($lessons as $lsn)
                        <option value="{{ $lsn->id }}">{{ $lsn->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">الوصف / التوجيهات للطلاب</label>
            <textarea name="description" class="form-control" rows="3" placeholder="اكتب تعليمات الاختبار للطلاب..."></textarea>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">مدة الاختبار (بالدقائق)</label>
                <input type="number" name="duration_minutes" class="form-control" value="15" required>
            </div>
            <div class="form-group">
                <label class="form-label">نسبة النجاح (%)</label>
                <input type="number" name="passing_score" class="form-control" value="50" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;"><i class="fas fa-arrow-left"></i> التالي: إضافة الأسئلة وتحديد الدرجات</button>
    </form>
</div>
@endsection

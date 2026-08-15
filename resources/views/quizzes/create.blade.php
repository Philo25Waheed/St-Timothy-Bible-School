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
            <label class="form-label">عنوان الاختبار <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="مثال: اختبار قصير على درس ميلاد السيد المسيح" required value="{{ old('title') }}">
        </div>
        
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">
                    توجيه الاختبار للفصل الدراسي 
                    @if(Auth::user()->isServant())
                        <span class="badge badge-info" style="font-size: 11px;">فصلك المسند</span>
                    @endif
                    <span class="text-danger">*</span>
                </label>
                <select name="class_id" class="form-control" required>
                    @if(Auth::user()->isAdmin())
                        <option value="">جميع الفصول (عام)</option>
                    @endif
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ (old('class_id') == $cls->id || (Auth::user()->isServant() && $classes->count() === 1)) ? 'selected' : '' }}>
                            {{ $cls->name }} ({{ $cls->grade->name ?? 'صف دراسي' }})
                        </option>
                    @endforeach
                </select>
                @if(Auth::user()->isServant())
                    <small class="text-muted d-block mt-1">يقتصر إنشاء الاختبارات على فصولك المسندة لخدمتك فقط.</small>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">الدرس المرتبط (اختياري)</label>
                <select name="lesson_id" class="form-control">
                    <option value="">اختبار عام / بدون درس محدد</option>
                    @foreach($lessons as $lsn)
                        <option value="{{ $lsn->id }}" {{ old('lesson_id') == $lsn->id ? 'selected' : '' }}>
                            {{ $lsn->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">الوصف / التوجيهات للطلاب</label>
            <textarea name="description" class="form-control" rows="3" placeholder="اكتب تعليمات الاختبار للطلاب وعدد الأسئلة...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">مدة الاختبار (بالدقائق) <span class="text-danger">*</span></label>
                <input type="number" name="duration_minutes" class="form-control" min="1" max="180" value="{{ old('duration_minutes', 15) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">نسبة النجاح المطلوبة (%) <span class="text-danger">*</span></label>
                <input type="number" name="passing_score" class="form-control" min="1" max="100" value="{{ old('passing_score', 50) }}" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 15px;">
            <i class="fas fa-arrow-left"></i> التالي: إضافة الأسئلة وتحديد الدرجات
        </button>
    </form>
</div>
@endsection

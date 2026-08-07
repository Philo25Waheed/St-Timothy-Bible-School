@extends('layouts.app')
@section('title', 'إنشاء امتحان جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إعداد امتحان رسمى جديد للفصل</h1>
    </div>
    <a href="{{ route('exams.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة</a>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <form action="{{ route('exams.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">عنوان الامتحان</label>
            <input type="text" name="title" class="form-control" placeholder="امتحان منتصف العام الدراسي" required>
        </div>

        <div class="form-group">
            <label class="form-label">توجيه الامتحان لفصل محدد (اختياري)</label>
            <select name="class_id" class="form-control">
                <option value="">جميع فصول الصف الدراسي</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">المرحلة الدراسية</label>
                <select name="stage_id" class="form-control">
                    <option value="">كل المراحل</option>
                    @foreach($stages as $stg)
                        <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الصف الدراسي</label>
                <select name="grade_id" class="form-control">
                    <option value="">كل الصفوف</option>
                    @foreach($stages->flatMap->grades as $grd)
                        <option value="{{ $grd->id }}">{{ $grd->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">المدة (بالدقائق)</label>
                <input type="number" name="duration_minutes" class="form-control" value="45" required>
            </div>
            <div class="form-group">
                <label class="form-label">درجة النجاح (%)</label>
                <input type="number" name="passing_score" class="form-control" value="50" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;"><i class="fas fa-arrow-left"></i> حفظ والانتقال للأسئلة</button>
    </form>
</div>
@endsection

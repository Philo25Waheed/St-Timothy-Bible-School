@extends('layouts.app')
@section('title', 'إنشاء منهج جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إنشاء منهج دراسي جديد</h1>
    </div>
    <a href="{{ route('curriculum.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('curriculum.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">عنوان المنهج</label>
            <input type="text" name="title" class="form-control" placeholder="منهج التربية الكنسية - الصف السادس" required>
        </div>
        <div class="form-group">
            <label class="form-label">المرحلة الدراسية</label>
            <select name="stage_id" class="form-control" required>
                @foreach($stages as $stg)
                    <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">الصف الدراسي</label>
            <select name="grade_id" class="form-control" required>
                @foreach($stages->flatMap->grades as $grd)
                    <option value="{{ $grd->id }}">{{ $grd->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">الوصف التفصيلي للمنهج</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ المنهج</button>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'إضافة فعالية جديدة')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إضافة فعالية بالتقويم</h1>
    </div>
    <a href="{{ route('events.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">عنوان الفعالية</label>
            <input type="text" name="title" class="form-control" required placeholder="رحلة دراسية لمكتبة الدير">
        </div>
        <div class="form-group">
            <label class="form-label">نوع الفعالية</label>
            <select name="event_type" class="form-control">
                <option value="trip">رحلة</option>
                <option value="exam">امتحان</option>
                <option value="activity">نشاط</option>
                <option value="church_event">مناسبة كنسية</option>
                <option value="meeting">اجتماع</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">المكان / الموقع</label>
            <input type="text" name="location" class="form-control" placeholder="قاعة الكنيسة الرئيسية">
        </div>
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label class="form-label">وقت البداية</label>
                <input type="datetime-local" name="start_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">وقت النهاية</label>
                <input type="datetime-local" name="end_time" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">التفاصيل والتوجيهات</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> إضافة الفعالية للتقويم</button>
    </form>
</div>
@endsection

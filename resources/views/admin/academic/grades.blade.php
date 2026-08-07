@extends('layouts.app')
@section('title', 'الصفوف الدراسية')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة الصفوف الدراسية</h1>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة صف دراسي</h3>
        <form action="{{ route('academic.grades.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">المرحلة الدراسية</label>
                <select name="stage_id" class="form-control" required>
                    @foreach($stages as $stg)
                        <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">اسم الصف (مثال: الصف السادس الابتدائي)</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">الترتيب</label>
                <input type="number" name="order" class="form-control" value="1">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ الصف</button>
        </form>
    </div>

    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-graduation-cap"></i> قائمة الصفوف الدراسية</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الصف</th>
                        <th>المرحلة</th>
                        <th>عدد الفصول</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grd)
                        <tr>
                            <td style="font-weight: 700;">{{ $grd->name }}</td>
                            <td>{{ $grd->stage->name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $grd->classes_count }} فصول</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

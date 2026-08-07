@extends('layouts.app')
@section('title', 'المراحل الدراسية')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة المراحل الدراسية</h1>
        <p class="page-subtitle">المراحل الرئيسية للمدرسة (ابتدائي، إعدادي، ثانوي)</p>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة مرحلة دراسية</h3>
        <form action="{{ route('academic.stages.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">اسم المرحلة (مثال: المرحلة الابتدائية)</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">وصف المرحلة</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">ترتيب العرض</label>
                <input type="number" name="order" class="form-control" value="1">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ المرحلة</button>
        </form>
    </div>

    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-layer-group"></i> المراحل الدراسية الحالية</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>المرحلة</th>
                        <th>عدد الصفوف</th>
                        <th>عدد الطلاب</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stages as $stg)
                        <tr>
                            <td style="font-weight: 700;">{{ $stg->name }}</td>
                            <td><span class="badge badge-info">{{ $stg->grades_count }} صفوف</span></td>
                            <td><span class="badge badge-success">{{ $stg->students_count }} طالب</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

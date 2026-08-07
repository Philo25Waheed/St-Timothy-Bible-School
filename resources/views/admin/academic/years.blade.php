@extends('layouts.app')
@section('title', 'السنوات الدراسية')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة السنوات الدراسية</h1>
        <p class="page-subtitle">إضافة وتفعيل العام الدراسي الحالي</p>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة عام دراسي جديد</h3>
        <form action="{{ route('academic.years.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">اسم السنة الدراسية (مثال: 2025/2026)</label>
                <input type="text" name="name" class="form-control" placeholder="2025/2026" required>
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ البداية</label>
                <input type="date" name="start_date" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ النهاية</label>
                <input type="date" name="end_date" class="form-control">
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <input type="checkbox" name="is_current" value="1" checked> تعيين كعام دراسي حالي نشط
                </label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> حفظ العام الدراسي</button>
        </form>
    </div>

    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-calendar-days"></i> قائمة السنوات الدراسية</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>السنة الدراسية</th>
                        <th>الحالة</th>
                        <th>الفترة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($years as $yr)
                        <tr>
                            <td style="font-weight: 700;">{{ $yr->name }}</td>
                            <td>
                                @if($yr->is_current)
                                    <span class="badge badge-success">العام الحالي النشط</span>
                                @else
                                    <span class="badge badge-warning">سابق</span>
                                @endif
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted);">
                                {{ $yr->start_date ? $yr->start_date->format('Y-m-d') : '-' }} إلى {{ $yr->end_date ? $yr->end_date->format('Y-m-d') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

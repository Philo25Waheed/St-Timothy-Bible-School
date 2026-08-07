@extends('layouts.app')
@section('title', 'تقرير الحضور والغياب')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تقرير الحضور والغياب التفصيلي</h1>
    </div>
</div>

<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <form action="{{ route('reports.attendance') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1;">
            <select name="class_id" class="form-control">
                <option value="">كل الفصول</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> تصفية</button>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الطالب</th>
                    <th>الفصل</th>
                    <th>الحالة</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr>
                        <td>{{ $rec->date ? $rec->date->format('Y-m-d') : '-' }}</td>
                        <td style="font-weight: 700;">{{ $rec->student->user->name ?? '-' }}</td>
                        <td>{{ $rec->schoolClass->name ?? '-' }}</td>
                        <td>
                            @switch($rec->status)
                                @case('present') <span class="badge badge-success">حاضر</span> @break
                                @case('late') <span class="badge badge-warning">متأخر</span> @break
                                @case('absent') <span class="badge badge-danger">غائب</span> @break
                                @case('excused') <span class="badge badge-info">بعذر</span> @break
                            @endswitch
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $rec->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">لا يوجد سجلات مطابقة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $records->links() }}
    </div>
</div>
@endsection

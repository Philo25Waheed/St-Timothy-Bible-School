@extends('layouts.app')
@section('title', 'تقرير أداء الفصل')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تقرير أداء الفصل</h1>
        <p class="page-subtitle">تحليل أداء ومعدلات الحضور والنتائج لفصل معين</p>
    </div>
</div>

<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <form action="{{ route('reports.class') }}" method="GET">
        <select name="class_id" class="form-control" onchange="this.form.submit()">
            <option value="">اختر الفصل...</option>
            @foreach($classes as $cls)
                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
            @endforeach
        </select>
    </form>
</div>

@if($selectedClass)
<div class="grid grid-cols-3" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div>
            <div class="stat-title">عدد طلاب الفصل</div>
            <div class="stat-value">{{ $classStats['total_students'] }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-title">معدل الحضور للفصل</div>
            <div class="stat-value">{{ $classStats['attendance_rate'] }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-calendar-check"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">متوسط نتائج الاختبارات</div>
            <div class="stat-value">{{ $classStats['avg_quiz_score'] }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-chart-bar"></i></div>
    </div>
</div>

<div class="card">
    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;">قائمة طلاب الفصل وأداؤهم</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>الطالب</th>
                    <th>نسبة الحضور</th>
                    <th>المتوسط الأكاديمي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedClass->students as $st)
                    <tr>
                        <td><code>{{ $st->code }}</code></td>
                        <td style="font-weight: 700;">{{ $st->user->name }}</td>
                        <td><span class="badge badge-success">{{ $st->attendance_rate }}%</span></td>
                        <td><span class="badge badge-warning">{{ $st->average_grade }}%</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

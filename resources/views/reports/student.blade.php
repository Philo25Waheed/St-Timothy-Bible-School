@extends('layouts.app')
@section('title', 'تقرير الطالب الشامل')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تقرير طالب شامل</h1>
        <p class="page-subtitle">اختر الطالب لعرض واستخراج التقرير التفصيلي</p>
    </div>
    @if($selectedStudent)
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> طباعة التقرير</button>
    @endif
</div>

<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <form action="{{ route('reports.student') }}" method="GET" style="display: flex; gap: 16px;">
        <div style="flex: 1;">
            <select name="student_id" class="form-control" onchange="this.form.submit()">
                <option value="">اختر الطالب...</option>
                @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ request('student_id') == $st->id ? 'selected' : '' }}>{{ $st->user->name }} (كود: {{ $st->code }})</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($selectedStudent)
<div class="card" id="printableReport" style="padding: 40px;">
    <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid var(--primary); padding-bottom: 20px;">
        <h2 style="font-size: 24px; font-weight: 900; color: var(--primary-dark);">تقرير الأداء الدراسي والروحي الشامل</h2>
        <div style="font-size: 14px; color: var(--text-muted);">مدرسة الكتاب المقدس - العام الدراسي {{ date('Y') }}</div>
    </div>

    <div class="grid grid-cols-2" style="margin-bottom: 30px;">
        <div>
            <div><strong>اسم الطالب:</strong> {{ $selectedStudent->user->name }}</div>
            <div><strong>الكود:</strong> {{ $selectedStudent->code }}</div>
            <div><strong>المرحلة والصف:</strong> {{ $selectedStudent->stage->name ?? '' }} - {{ $selectedStudent->grade->name ?? '' }}</div>
        </div>
        <div>
            <div><strong>الفصل:</strong> {{ $selectedStudent->schoolClass->name ?? '' }}</div>
            <div><strong>نسبة الحضور:</strong> {{ $selectedStudent->attendance_rate }}%</div>
            <div><strong>المتوسط العام:</strong> {{ $selectedStudent->average_grade }}%</div>
        </div>
    </div>

    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 12px;">نتائج الاختبارات الأخيرة</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الاختبار</th>
                    <th>الدرجة</th>
                    <th>النسبة المئوية</th>
                    <th>النتيجة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedStudent->quizAttempts as $qa)
                    <tr>
                        <td>{{ $qa->quiz->title }}</td>
                        <td>{{ $qa->score }} / {{ $qa->total_marks }}</td>
                        <td>{{ $qa->percentage }}%</td>
                        <td><span class="badge {{ $qa->passed ? 'badge-success' : 'badge-danger' }}">{{ $qa->passed ? 'ناجح' : 'راسب' }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

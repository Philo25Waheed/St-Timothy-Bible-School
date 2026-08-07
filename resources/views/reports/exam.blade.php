@extends('layouts.app')
@section('title', 'تقرير الامتحانات والاختبارات')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تقرير نتائج الاختبارات والامتحانات</h1>
    </div>
</div>

<div class="grid grid-cols-2" style="margin-bottom: 30px;">
    <div class="stat-card green">
        <div>
            <div class="stat-title">نسبة نجاح الاختبارات القصيرة</div>
            <div class="stat-value">{{ $quizPassRate }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-check-circle"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">نسبة نجاح الامتحانات الرسمية</div>
            <div class="stat-value">{{ $examPassRate }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-award"></i></div>
    </div>
</div>

<div class="card">
    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;">أحدث تقديمات الاختبارات</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>اسم الاختبار</th>
                    <th>الدرجة</th>
                    <th>النسبة المئوية</th>
                    <th>النتيجة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizAttempts as $qa)
                    <tr>
                        <td style="font-weight: 700;">{{ $qa->student->user->name ?? '-' }}</td>
                        <td>{{ $qa->quiz->title }}</td>
                        <td>{{ $qa->score }} / {{ $qa->total_marks }}</td>
                        <td>{{ $qa->percentage }}%</td>
                        <td><span class="badge {{ $qa->passed ? 'badge-success' : 'badge-danger' }}">{{ $qa->passed ? 'ناجح' : 'راسب' }}</span></td>
                        <td>{{ $qa->completed_at ? $qa->completed_at->format('Y-m-d') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

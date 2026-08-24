@extends('layouts.app')
@section('title', 'لوحة تحكم المسؤول')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">مرحبًا بك في لوحة التحكم 👋</h1>
        <p class="page-subtitle">نظرة عامة على إحصائيات وأداء مدرسة الكتاب المقدس</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> إضافة طالب</a>
        <a href="{{ route('servants.create') }}" class="btn btn-accent btn-sm"><i class="fas fa-user-tie"></i> إضافة خادم</a>
        <a href="{{ route('admins.create') }}" class="btn btn-outline btn-sm"><i class="fas fa-user-shield"></i> إضافة مسئول</a>
        <a href="{{ route('quizzes.create') }}" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> إنشاء اختبار</a>
    </div>
</div>

<!-- 8 KPI Statistics Cards -->
<div class="grid grid-cols-4" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div>
            <div class="stat-title">إجمالي الطلاب</div>
            <div class="stat-value">{{ $stats['total_students'] }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">إجمالي الخدام</div>
            <div class="stat-value">{{ $stats['total_servants'] }}</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-user-tie"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-title">أولياء الأمور</div>
            <div class="stat-value">{{ $stats['total_parents'] }}</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card purple">
        <div>
            <div class="stat-title">عدد الفصول</div>
            <div class="stat-value">{{ $stats['total_classes'] }}</div>
        </div>
        <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-school"></i></div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-title">المراحل الدراسية</div>
            <div class="stat-value">{{ $stats['total_stages'] }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">عدد الدروس</div>
            <div class="stat-value">{{ $stats['total_lessons'] }}</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-book-open"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-title">عدد الاختبارات</div>
            <div class="stat-value">{{ $stats['total_exams'] }}</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-file-pen"></i></div>
    </div>
    <div class="stat-card purple">
        <div>
            <div class="stat-title">نسبة الحضور العريضة</div>
            <div class="stat-value">{{ $stats['overall_attendance_rate'] }}%</div>
        </div>
        <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-chart-pie"></i></div>
    </div>
</div>

<!-- Interactive Chart Widgets -->
<div class="grid grid-cols-2" style="margin-bottom: 30px;">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-chart-line" style="color: var(--primary-light);"></i> نسبة الحضور خلال الأسبوع (Attendance Trend)</h3>
        <canvas id="attendanceChart" height="200"></canvas>
    </div>
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-chart-pie" style="color: var(--accent);"></i> توزيع الطلاب حسب المراحل (Students by Stage)</h3>
        <canvas id="stageChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-2" style="margin-bottom: 30px;">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-chart-bar" style="color: var(--success);"></i> أداء الفصول والدرجات (Class Performance)</h3>
        <canvas id="classPerformanceChart" height="200"></canvas>
    </div>

    <!-- Recent Activity Timeline -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-clock-rotate-left" style="color: #8b5cf6;"></i> أحدث النشاطات في النظام (Recent Activity)</h3>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($recentStudents as $student)
                <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                    <span class="badge badge-success"><i class="fas fa-user-plus"></i> طالب جديد</span>
                    <span style="font-weight: 700;">{{ $student->user->name }}</span>
                    <span style="color: var(--text-muted);">انضم لـ {{ $student->schoolClass->name ?? 'الفصل' }}</span>
                </div>
            @endforeach

            @foreach($recentQuizAttempts as $attempt)
                <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                    <span class="badge badge-info"><i class="fas fa-check"></i> نتيجة اختبار</span>
                    <span style="font-weight: 700;">{{ $attempt->student->user->name }}</span>
                    <span style="color: var(--text-muted);">حصل على {{ $attempt->percentage }}% في {{ $attempt->quiz->title }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Attendance Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: @json($attendanceChart['labels'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
            datasets: [{
                label: 'نسبة الحضور %',
                data: @json($attendanceChart['data'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
                borderColor: '#1e3a8a',
                backgroundColor: 'rgba(30, 58, 138, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Stage Distribution Donut
    new Chart(document.getElementById('stageChart'), {
        type: 'doughnut',
        data: {
            labels: @json($stageChart['labels'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
            datasets: [{
                data: @json($stageChart['data'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
                backgroundColor: ['#1e3a8a', '#d97706', '#10b981', '#8b5cf6']
            }]
        },
        options: { responsive: true }
    });

    // Class Performance Bar
    new Chart(document.getElementById('classPerformanceChart'), {
        type: 'bar',
        data: {
            labels: @json($classPerformance['labels'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
            datasets: [{
                label: 'متوسط الأداء %',
                data: @json($classPerformance['data'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
                backgroundColor: '#d97706',
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
@endsection

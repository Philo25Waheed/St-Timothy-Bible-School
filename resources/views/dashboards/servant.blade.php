@extends('layouts.app')
@section('title', 'لوحة تحكم الخادم')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">أهلاً بك يا {{ Auth::user()->name }} 👋</h1>
        <p class="page-subtitle">إدارة فصولك المسندة ومتابعة حضور ونشاط الطلاب</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-user"></i> تسجيل الحضور اليومي</a>
        <a href="{{ route('quizzes.create') }}" class="btn btn-accent btn-sm"><i class="fas fa-plus"></i> إنشاء Quiz جديد</a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-4" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div>
            <div class="stat-title">عدد الطلاب بفصولك</div>
            <div class="stat-value">{{ $studentsCount }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-title">حضور اليوم</div>
            <div class="stat-value">{{ $todayAttendanceRate }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-calendar-check"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">عدد الفصول المسندة</div>
            <div class="stat-value">{{ $assignedClasses->count() }}</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-school"></i></div>
    </div>
    <div class="stat-card purple">
        <div>
            <div class="stat-title">الاختبارات القادمة</div>
            <div class="stat-value">{{ $upcomingQuizzes->count() }}</div>
        </div>
        <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-tasks"></i></div>
    </div>
</div>

<!-- Assigned Classes & Quick Actions -->
<div class="card">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-school" style="color: var(--primary);"></i> فصولي الدراسية</h3>
    <div class="grid grid-cols-3">
        @forelse($assignedClasses as $class)
            <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 16px; border-radius: var(--radius-sm);">
                <div style="font-size: 16px; font-weight: 800; color: var(--primary-dark);">{{ $class->name }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">{{ $class->grade->name ?? '' }} - {{ $class->room }}</div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('attendance.index', ['class_id' => $class->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-user-check"></i> أخذ الحضور</a>
                    <a href="{{ route('reports.class', ['class_id' => $class->id]) }}" class="btn btn-outline btn-sm"><i class="fas fa-chart-column"></i> تقرير الفصل</a>
                </div>
            </div>
        @empty
            <div style="color: var(--text-muted);">لا يوجد فصول مسندة إليك حالياً.</div>
        @endforelse
    </div>
</div>

<!-- Student List in Assigned Class -->
<div class="card">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-users-view-finder"></i> قائمة طلاب فصلي</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>كود الطالب</th>
                    <th>الاسم</th>
                    <th>الفصل</th>
                    <th>إجمالي النقاط</th>
                    <th>الأوسمة</th>
                    <th>إجراء سريعة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classStudents as $st)
                    <tr>
                        <td><code>{{ $st->code }}</code></td>
                        <td style="font-weight: 700;">{{ $st->user->name }}</td>
                        <td>{{ $st->schoolClass->name ?? '-' }}</td>
                        <td><span class="badge badge-warning">+{{ $st->total_points }} نقطة</span></td>
                        <td>
                            @foreach($st->achievements as $ach)
                                <span class="badge badge-info"><i class="{{ $ach->icon }}"></i> {{ $ach->title }}</span>
                            @endforeach
                        </td>
                        <td>
                            <!-- Add Points Trigger -->
                            <form action="{{ route('points.store') }}" method="POST" style="display: inline-flex; gap: 4px;">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $st->id }}">
                                <input type="hidden" name="amount" value="5">
                                <input type="hidden" name="reason" value="تشجيع من الخادم">
                                <input type="hidden" name="category" value="general">
                                <button type="submit" class="btn btn-accent btn-sm" title="إضافة 5 نقاط"><i class="fas fa-plus"></i> +5 نقاط</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">لا يوجد طلاب مسجلون في الفصل حالياً.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

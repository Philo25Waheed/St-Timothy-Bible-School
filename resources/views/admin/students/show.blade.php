@extends('layouts.app')
@section('title', 'الملف التفصيلي للطالب: ' . $student->user->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $student->user->name }}</h1>
        <p class="page-subtitle">كود الطالب: <code>{{ $student->code }}</code> | {{ $student->stage->name ?? '' }} - {{ $student->schoolClass->name ?? '' }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline"><i class="fas fa-pen-to-square"></i> تعديل</a>
        <a href="{{ route('students.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للقائمة</a>
    </div>
</div>

<!-- Header Card -->
<div class="card" style="background: linear-gradient(135deg, #0f172a, #1e3a8a); color: white; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="{{ $student->user->avatar_url }}" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--accent-gold);">
            <div>
                <h2 style="font-size: 24px; font-weight: 800;">{{ $student->user->name }}</h2>
                <div style="font-size: 14px; color: #94a3b8; margin-top: 4px;">
                    <i class="fas fa-envelope"></i> {{ $student->user->email }} | 
                    <i class="fas fa-phone"></i> {{ $student->user->phone ?? 'لا يوجد هاتف' }}
                </div>
                <div style="margin-top: 8px; font-size: 13px; color: var(--accent-gold);">
                    <i class="fas fa-users-between-lines"></i> ولي الأمر: {{ $student->parentUser->name ?? 'غير محدد' }} | 
                    <i class="fas fa-user-tie"></i> الخادم: {{ $student->servantUser->name ?? 'غير محدد' }}
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 16px;">
            <div style="text-align: center; background: rgba(255,255,255,0.1); padding: 12px 20px; border-radius: var(--radius-sm);">
                <div style="font-size: 11px; color: #94a3b8;">نسبة الحضور</div>
                <div style="font-size: 22px; font-weight: 900; color: #10b981;">{{ $student->attendance_rate }}%</div>
            </div>
            <div style="text-align: center; background: rgba(255,255,255,0.1); padding: 12px 20px; border-radius: var(--radius-sm);">
                <div style="font-size: 11px; color: #94a3b8;">المتوسط العام</div>
                <div style="font-size: 22px; font-weight: 900; color: var(--accent-gold);">{{ $student->average_grade }}%</div>
            </div>
            <div style="text-align: center; background: rgba(255,255,255,0.1); padding: 12px 20px; border-radius: var(--radius-sm);">
                <div style="font-size: 11px; color: #94a3b8;">النقاط</div>
                <div style="font-size: 22px; font-weight: 900; color: #8b5cf6;">{{ $student->total_points }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Section -->
<div class="card">
    <div style="border-bottom: 2px solid var(--border-color); margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 800;"><i class="fas fa-layer-group" style="color: var(--primary);"></i> السجل الشامل للأنشطة والدرجات</h3>
    </div>

    <!-- Attendance Records -->
    <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 12px; color: var(--primary);"><i class="fas fa-calendar-check"></i> سجل الحضور</h4>
    <div class="table-responsive" style="margin-bottom: 30px;">
        <table class="table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الفصل</th>
                    <th>الحالة</th>
                    <th>المسجل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->attendanceRecords as $att)
                    <tr>
                        <td>{{ $att->date ? $att->date->format('Y-m-d') : '-' }}</td>
                        <td>{{ $att->schoolClass->name ?? '-' }}</td>
                        <td>
                            @switch($att->status)
                                @case('present') <span class="badge badge-success">حاضر</span> @break
                                @case('late') <span class="badge badge-warning">متأخر</span> @break
                                @case('absent') <span class="badge badge-danger">غائب</span> @break
                            @endswitch
                        </td>
                        <td>{{ $att->recorder->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">لا يوجد سجلات حضور.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quiz Attempts -->
    <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 12px; color: var(--accent);"><i class="fas fa-tasks"></i> نتائح الاختبارات القصيرة (Quizzes)</h4>
    <div class="table-responsive" style="margin-bottom: 30px;">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الاختبار</th>
                    <th>الدرجة</th>
                    <th>النسبة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->quizAttempts as $qa)
                    <tr>
                        <td>{{ $qa->quiz->title }}</td>
                        <td>{{ $qa->score }} / {{ $qa->total_marks }}</td>
                        <td>{{ $qa->percentage }}%</td>
                        <td><span class="badge {{ $qa->passed ? 'badge-success' : 'badge-danger' }}">{{ $qa->passed ? 'ناجح' : 'راسب' }}</span></td>
                        <td>{{ $qa->completed_at ? $qa->completed_at->format('Y-m-d') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">لا يوجد اختبارات قصيرة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Points Audit Log -->
    <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 12px; color: #8b5cf6;"><i class="fas fa-star"></i> سجل النقاط والمكافآت</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>النقاط</th>
                    <th>السبب</th>
                    <th>الفئة</th>
                    <th>المانح</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->points as $pt)
                    <tr>
                        <td><span class="badge {{ $pt->amount > 0 ? 'badge-success' : 'badge-danger' }}">{{ $pt->amount > 0 ? '+' : '' }}{{ $pt->amount }} نقطة</span></td>
                        <td>{{ $pt->reason }}</td>
                        <td>{{ $pt->category }}</td>
                        <td>{{ $pt->giver->name ?? '-' }}</td>
                        <td>{{ $pt->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">لا يوجد سجل نقاط.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

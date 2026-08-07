@extends('layouts.app')
@section('title', 'لوحة تحكم ولي الأمر')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">أهلاً بك يا {{ Auth::user()->name }} 👋</h1>
        <p class="page-subtitle">متابعة الأبناء وحضورهم ونتائجهم الدراسية</p>
    </div>
</div>

<!-- Children Switcher Tabs -->
<div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid var(--border-color); padding-bottom: 12px;">
    @foreach($children as $child)
        <a href="{{ route('dashboard', ['child_id' => $child->id]) }}" 
           class="btn {{ $selectedChild->id === $child->id ? 'btn-primary' : 'btn-outline' }}"
           style="border-radius: 20px; font-size: 15px; padding: 10px 22px;">
            <i class="fas {{ $child->user->gender === 'female' ? 'fa-child-dress' : 'fa-child' }}"></i>
            {{ $child->user->name }} ({{ $child->grade->name ?? 'الصف' }})
        </a>
    @endforeach
</div>

<!-- Selected Child Overview Banner -->
<div class="card" style="background: linear-gradient(135deg, #0f172a, #1e3a8a); color: white; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="{{ $selectedChild->user->avatar_url }}" style="width: 70px; height: 70px; border-radius: 50%; border: 3px solid var(--accent-gold);">
            <div>
                <h2 style="font-size: 22px; font-weight: 800;">{{ $selectedChild->user->name }}</h2>
                <p style="color: #94a3b8; font-size: 14px;">{{ $selectedChild->stage->name ?? '' }} - {{ $selectedChild->schoolClass->name ?? '' }}</p>
                <div style="margin-top: 6px; font-size: 12px; color: var(--accent-gold);">
                    <i class="fas fa-user-tie"></i> الخادم المسؤول: {{ $selectedChild->servantUser->name ?? 'غير محدد' }}
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            @if($selectedChild->servantUser)
                <a href="{{ route('messages.index', ['user_id' => $selectedChild->servantUser->id]) }}" class="btn btn-accent">
                    <i class="fas fa-comment-dots"></i> التواصل مع الخادم
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-4" style="margin-bottom: 30px;">
    <div class="stat-card green">
        <div>
            <div class="stat-title">نسبة الحضور</div>
            <div class="stat-value">{{ $selectedChild->attendance_rate }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-user-check"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">متوسط الدرجات</div>
            <div class="stat-value">{{ $selectedChild->average_grade }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-chart-line"></i></div>
    </div>
    <div class="stat-card purple">
        <div>
            <div class="stat-title">إجمالي النقاط</div>
            <div class="stat-value">{{ $selectedChild->total_points }}</div>
        </div>
        <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-star"></i></div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-title">الأوسمة والمكافآت</div>
            <div class="stat-value">{{ $selectedChild->achievements->count() }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
    </div>
</div>

<!-- Child Quiz & Exam Results Section -->
<div class="card" style="margin-bottom: 30px; border-top: 4px solid var(--accent);">
    <h3 style="font-size: 16px; font-weight: 800; color: var(--primary-dark); margin-bottom: 16px;">
        <i class="fas fa-file-signature" style="color: var(--accent);"></i> نتائج الاختبارات والامتحانات الخاصة بـ ({{ $selectedChild->user->name }})
    </h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الاختبار / الامتحان</th>
                    <th>الدرجة الحاصل عليها</th>
                    <th>النسبة المئوية</th>
                    <th>النتيجة النهائية</th>
                    <th>تاريخ التقديم</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizAttempts as $qa)
                    <tr>
                        <td style="font-weight: 700;">
                            <i class="fas fa-tasks" style="color: var(--accent); margin-left: 6px;"></i>
                            {{ $qa->quiz->title }}
                        </td>
                        <td>{{ $qa->score }} / {{ $qa->total_marks }}</td>
                        <td style="font-weight: 800;">{{ $qa->percentage }}%</td>
                        <td>
                            @if($qa->passed)
                                <span class="badge badge-success">ناجح ✓</span>
                            @else
                                <span class="badge badge-danger">غير ناجح</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $qa->completed_at ? $qa->completed_at->format('Y-m-d H:i') : '-' }}</td>
                    </tr>
                @empty
                @endforelse

                @forelse($examAttempts as $ea)
                    <tr>
                        <td style="font-weight: 700;">
                            <i class="fas fa-file-pen" style="color: var(--primary); margin-left: 6px;"></i>
                            {{ $ea->exam->title }}
                        </td>
                        <td>{{ $ea->score }} / {{ $ea->total_marks }}</td>
                        <td style="font-weight: 800;">{{ $ea->percentage }}%</td>
                        <td>
                            @if($ea->passed)
                                <span class="badge badge-success">ناجح ✓</span>
                            @else
                                <span class="badge badge-danger">غير ناجح</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $ea->completed_at ? $ea->completed_at->format('Y-m-d H:i') : '-' }}</td>
                    </tr>
                @empty
                @endforelse

                @if($quizAttempts->isEmpty() && $examAttempts->isEmpty())
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">لم يقم الطالب بإجراء أي اختبارات أو امتحانات حتى الآن.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Attendance & Verse Progress Tabs -->
<div class="grid grid-cols-2" style="margin-bottom: 30px;">
    <!-- Attendance Calendar Record -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-calendar-days" style="color: var(--primary);"></i> سجل الحضور والغياب الأخيرة</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceRecords as $rec)
                        <tr>
                            <td>{{ $rec->date ? $rec->date->format('Y-m-d') : '-' }}</td>
                            <td>
                                @switch($rec->status)
                                    @case('present') <span class="badge badge-success">حاضر ✓</span> @break
                                    @case('late') <span class="badge badge-warning">متأخر</span> @break
                                    @case('absent') <span class="badge badge-danger">غائب ✗</span> @break
                                    @case('excused') <span class="badge badge-info">غياب بعذر</span> @break
                                @endswitch
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted);">{{ $rec->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">لا يوجد سجلات حضور حالياً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bible Verse Progress -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-quote-right" style="color: var(--accent);"></i> تقدم حفظ الآيات الكتابية</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الآية وشاهدها</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($verseProgress as $vp)
                        <tr>
                            <td>
                                <div style="font-weight: 700; font-size: 13px;">«{{ $vp->bibleVerse->text }}»</div>
                                <div style="font-size: 11px; color: var(--text-muted);">({{ $vp->bibleVerse->reference }})</div>
                            </td>
                            <td>
                                @switch($vp->status)
                                    @case('excellent') <span class="badge badge-success">ممتاز 🌟</span> @break
                                    @case('completed') <span class="badge badge-info">تم التسميع ✓</span> @break
                                    @case('in_review') <span class="badge badge-warning">جاري المراجعة</span> @break
                                    @default <span class="badge badge-danger">لم يتم التسميع</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="text-align: center; color: var(--text-muted);">لم يتم تسجيل آيات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

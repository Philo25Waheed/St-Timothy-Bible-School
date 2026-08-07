@extends('layouts.app')
@section('title', 'لوحة تحكم الطالب')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">أهلاً بك يا {{ Auth::user()->name }} 👋</h1>
        <p class="page-subtitle">رحلتك التعليمية والروحية بـ {{ $student->grade->name ?? 'مدرسة الكتاب المقدس' }}</p>
    </div>
    <div>
        <span style="background: linear-gradient(135deg, #ef4444, #d97706); color: white; padding: 8px 18px; border-radius: 20px; font-weight: 800; font-size: 14px; box-shadow: 0 4px 12px rgba(239,68,68,0.3);">
            🔥 {{ $streakWeeks }} أسابيع متتالية
        </span>
    </div>
</div>

<!-- Curriculum Progress Bar -->
<div class="card" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: white; padding: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 800;">تقدمك في المنهج الدراسي</h3>
            <p style="font-size: 13px; color: #94a3b8;">أنجزت {{ $completedLessonsCount }} من إجمالي {{ $totalLessons }} درس</p>
        </div>
        <div style="font-size: 28px; font-weight: 900; color: var(--accent-gold);">{{ $curriculumProgress }}%</div>
    </div>
    <div class="progress-bar-bg" style="height: 12px; background: rgba(255,255,255,0.15);">
        <div class="progress-bar-fill" style="width: {{ $curriculumProgress }}%;"></div>
    </div>
</div>

<!-- 4 Statistics Cards -->
<div class="grid grid-cols-4" style="margin-bottom: 30px;">
    <div class="stat-card green">
        <div>
            <div class="stat-title">نسبة الحضور</div>
            <div class="stat-value">{{ $attendanceRate }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-calendar-check"></i></div>
    </div>
    <div class="stat-card gold">
        <div>
            <div class="stat-title">متوسط الدرجات</div>
            <div class="stat-value">{{ $averageGrade }}%</div>
        </div>
        <div class="stat-icon" style="color: var(--accent);"><i class="fas fa-award"></i></div>
    </div>
    <div class="stat-card purple">
        <div>
            <div class="stat-title">نقاط التشجيع</div>
            <div class="stat-value">{{ $totalPoints }}</div>
        </div>
        <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-star"></i></div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-title">الدروس المكتملة</div>
            <div class="stat-value">{{ $completedLessonsCount }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-book-open"></i></div>
    </div>
</div>

<!-- Hero Continue Learning Card -->
@if($nextLesson)
<div class="card" style="border-right: 6px solid var(--accent); background: #fffdf5;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <span class="badge badge-warning" style="margin-bottom: 8px;">الدرس التالي للتعلّم</span>
            <h2 style="font-size: 20px; font-weight: 800; color: var(--primary-dark);">{{ $nextLesson->title }}</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">{{ Str::limit($nextLesson->description, 120) }}</p>
        </div>
        <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn btn-accent" style="padding: 12px 24px; font-size: 15px;">
            <i class="fas fa-play"></i> متابعة التعلم
        </a>
    </div>
</div>
@endif

<!-- Class Quizzes & Exams Assigned to Student -->
<div class="card" style="border-top: 4px solid var(--primary-light);">
    <h3 style="font-size: 16px; font-weight: 800; color: var(--primary-dark); margin-bottom: 16px;">
        <i class="fas fa-file-pen" style="color: var(--primary-light);"></i> امتحانات واختبارات فصلي ({{ $student->schoolClass->name ?? 'الفصل' }})
    </h3>
    <div class="grid grid-cols-2">
        <div>
            <h4 style="font-size: 14px; font-weight: 700; color: var(--accent); margin-bottom: 10px;">الاختبارات القصيرة المتاحة</h4>
            @forelse($upcomingClassQuizzes as $qz)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; font-size: 14px;">{{ $qz->title }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">المدة: {{ $qz->duration_minutes }} دقيقة | الدرجات: {{ $qz->total_marks }}</div>
                    </div>
                    <a href="{{ route('quizzes.take', $qz->id) }}" class="btn btn-accent btn-sm"><i class="fas fa-play"></i> تقديم الاختبار</a>
                </div>
            @empty
                <div style="font-size: 13px; color: var(--text-muted);">لا يوجد اختبارات قصيرة متاحة حالياً.</div>
            @endforelse
        </div>

        <div>
            <h4 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 10px;">الامتحانات الرسمية المتاحة</h4>
            @forelse($upcomingClassExams as $ex)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; font-size: 14px;">{{ $ex->title }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">المدة: {{ $ex->duration_minutes }} دقيقة | الدرجة: {{ $ex->total_marks }}</div>
                    </div>
                    <a href="{{ route('exams.take', $ex->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-file-signature"></i> تقديم الامتحان</a>
                </div>
            @empty
                <div style="font-size: 13px; color: var(--text-muted);">لا يوجد امتحانات رسمية متاحة حالياً.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Weekly Memory Verse & Badges -->
<div class="grid grid-cols-2" style="margin-bottom: 30px;">
    <!-- Memory Verse -->
    <div class="card" style="background: linear-gradient(135deg, #1e3a8a, #0f172a); color: white;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--accent-gold); margin-bottom: 12px;">
            <i class="fas fa-quote-right"></i> آية الحفظ الأسبوعية
        </h3>
        @if($weeklyVerse)
            <div style="font-size: 16px; font-weight: 700; line-height: 1.8; margin-bottom: 12px; font-style: italic;">
                «{{ $weeklyVerse->text }}»
            </div>
            <div style="text-align: left; font-size: 13px; color: #94a3b8; font-weight: bold;">
                ({{ $weeklyVerse->reference }})
            </div>
        @else
            <p>«الرَّبُّ يَقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ» (خر 14: 14)</p>
        @endif
    </div>

    <!-- Achievements / Badges -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-trophy" style="color: var(--accent);"></i> أوسمتي وإنجازاتي</h3>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @forelse($student->achievements as $ach)
                <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: var(--radius-sm); padding: 10px 16px; text-align: center;">
                    <i class="{{ $ach->icon }}" style="font-size: 24px; color: var(--accent); margin-bottom: 4px;"></i>
                    <div style="font-size: 12px; font-weight: 800; color: #92400e;">{{ $ach->title }}</div>
                </div>
            @empty
                <p style="color: var(--text-muted); font-size: 13px;">واصل التعلم واجتياز الاختبارات للحصول على الأوسمة!</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Grades & Exams -->
<div class="card">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-file-signature"></i> نتائج الاختبارات الأخيرة</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الاختبار</th>
                    <th>الدرجة</th>
                    <th>النسبة المئوية</th>
                    <th>الحالة</th>
                    <th>تاريخ الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentQuizzes as $attempt)
                    <tr>
                        <td style="font-weight: 700;">{{ $attempt->quiz->title }}</td>
                        <td>{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                        <td>{{ $attempt->percentage }}%</td>
                        <td>
                            @if($attempt->passed)
                                <span class="badge badge-success">ناجح ✓</span>
                            @else
                                <span class="badge badge-danger">حاول مرة أخرى</span>
                            @endif
                        </td>
                        <td>{{ $attempt->completed_at ? $attempt->completed_at->format('Y-m-d') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">لم تقم بإجراء أي اختبارات بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

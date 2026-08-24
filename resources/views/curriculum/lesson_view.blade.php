@extends('layouts.app')
@section('title', $lesson->title)

@section('content')
<div class="page-header">
    <div>
        <span class="badge badge-info" style="margin-bottom: 6px;">{{ $lesson->unit->curriculum->title ?? '' }} » {{ $lesson->unit->title ?? '' }}</span>
        <h1 class="page-title">{{ $lesson->title }}</h1>
    </div>
    <div style="display: flex; gap: 10px;">
        @if(Auth::user()->isStudent())
            @if($isCompleted)
                <span class="badge badge-success" style="padding: 10px 16px; font-size: 14px;"><i class="fas fa-check-double"></i> مكتمل تم الإنهاء ✓</span>
            @else
                <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-accent"><i class="fas fa-check"></i> تحديد كـ "مكتمل"</button>
                </form>
            @endif
        @endif

        <a href="{{ route('curriculum.show', $lesson->unit->curriculum_id ?? 1) }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للمنهج</a>
    </div>
</div>

<div class="grid grid-cols-3">
    <!-- Main Reading Content Frame -->
    <div style="grid-column: span 2;">
        <div class="card" style="padding: 36px; border-top: 5px solid var(--primary-light);">
            <!-- Bible Verse Callout Box -->
            @if($lesson->bible_verse)
                <div style="background: #f1f5f9; border-right: 4px solid var(--primary); padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 24px;">
                    <div style="font-size: 12px; font-weight: 800; color: var(--primary);"><i class="fas fa-bible"></i> قراءة الكتاب المقدس:</div>
                    <div style="font-size: 16px; font-weight: 700; color: var(--primary-dark); margin-top: 4px;">{{ $lesson->bible_verse }}</div>
                </div>
            @endif

            <!-- Lesson Content HTML -->
            <div class="lesson-html-content" style="font-size: 16px; line-height: 1.9; color: var(--text-main); margin-bottom: 30px;">
                {!! strip_tags($lesson->content, '<p><br><b><strong><i><em><u><h2><h3><h4><h5><ul><ol><li><blockquote><span><div>') !!}
            </div>

            <!-- Video Embed Section if available -->
            @if($lesson->video_url)
                @php
                    $videoUrl = $lesson->video_url;
                    if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                        $videoUrl = preg_replace('/.*v=([a-zA-Z0-9_-]+).*/', 'https://www.youtube-nocookie.com/embed/$1', $videoUrl);
                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                        $videoUrl = preg_replace('/.*youtu\.be\/([a-zA-Z0-9_-]+).*/', 'https://www.youtube-nocookie.com/embed/$1', $videoUrl);
                    }
                @endphp
                <div style="margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-video" style="color: var(--danger);"></i> الفيديو الشارح للدرس</h3>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-md);">
                        <iframe src="{{ $videoUrl }}" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;" sandbox="allow-scripts allow-same-origin allow-presentation" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Sidebar Widgets -->
    <div>
        <!-- Memory Verse Box -->
        @if($lesson->memory_verse)
            <div class="card" style="background: linear-gradient(135deg, #1e3a8a, #0f172a); color: white; margin-bottom: 20px;">
                <div style="font-size: 14px; font-weight: 800; color: var(--accent-gold); margin-bottom: 10px;">
                    <i class="fas fa-quote-right"></i> آية الحفظ للدرس
                </div>
                <div style="font-size: 15px; font-weight: 700; line-height: 1.7; font-style: italic;">
                    {{ $lesson->memory_verse }}
                </div>
            </div>
        @endif

        <!-- Quizzes Available for this Lesson -->
        <div class="card">
            <h3 style="font-size: 15px; font-weight: 800; margin-bottom: 14px;"><i class="fas fa-tasks" style="color: var(--accent);"></i> اختبارات هذا الدرس</h3>
            @forelse($lesson->quizzes as $quiz)
                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 12px; border-radius: var(--radius-sm); margin-bottom: 10px;">
                    <div style="font-weight: 700; font-size: 14px; color: var(--primary-dark);">{{ $quiz->title }}</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin: 4px 0 8px;">المدة: {{ $quiz->duration_minutes }} دقيقة</div>
                    <a href="{{ route('quizzes.take', $quiz->id) }}" class="btn btn-accent btn-sm" style="width: 100%; justify-content: center;">
                        <i class="fas fa-pen"></i> بدء الاختبار
                    </a>
                </div>
            @empty
                <div style="font-size: 13px; color: var(--text-muted);">لا يوجد اختبار مخصص لهذا الدرس حالياً.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

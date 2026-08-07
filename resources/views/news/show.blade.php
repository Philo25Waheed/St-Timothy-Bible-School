@extends('layouts.app')
@section('title', $news->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $news->title }}</h1>
        <p class="page-subtitle">تاريخ النشر: {{ $news->published_at ? $news->published_at->format('Y-m-d') : '' }}</p>
    </div>
    <a href="{{ route('news.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للأخبار</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto; padding: 40px;">
    <div style="font-size: 16px; line-height: 2; color: var(--text-main);">
        {!! nl2br(e($news->content)) !!}
    </div>
</div>
@endsection

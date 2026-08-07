@extends('layouts.app')
@section('title', 'أخبار الكنيسة والمدرسة')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">أخبار الكنيسة ومدرسة الكتاب المقدس</h1>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isServant())
        <a href="{{ route('news.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> نشر خبر جديد</a>
    @endif
</div>

<div class="grid grid-cols-3">
    @forelse($newsList as $item)
        <div class="card">
            <span class="badge badge-info" style="margin-bottom: 8px;">{{ $item->published_at ? $item->published_at->format('Y-m-d') : '' }}</span>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark); margin-bottom: 10px;">{{ $item->title }}</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">{{ Str::limit($item->content, 120) }}</p>
            <a href="{{ route('news.show', $item->id) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> قراءة الخبر بالتفصيل</a>
        </div>
    @empty
        <div style="grid-column: span 3; text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد أخبار حالياً.</div>
    @endforelse
</div>
@endsection

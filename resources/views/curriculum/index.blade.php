@extends('layouts.app')
@section('title', 'المناهج والدروس الدراسية')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">المناهج والدروس الدراسية</h1>
        <p class="page-subtitle">استعراض المناهج والوحدات والدروس لجميع المراحل</p>
    </div>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('curriculum.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء منهج دراسي جديد</a>
    @endif
</div>

<div class="grid grid-cols-3">
    @forelse($curricula as $curr)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <span class="badge badge-info" style="margin-bottom: 8px;">{{ $curr->stage->name ?? '' }} - {{ $curr->grade->name ?? '' }}</span>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">{{ $curr->title }}</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">{{ Str::limit($curr->description, 100) }}</p>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                    <i class="fas fa-layer-group"></i> {{ $curr->units->count() }} وحدات دراسية | 
                    <i class="fas fa-book"></i> {{ $curr->units->flatMap->lessons->count() }} درس
                </div>
                <a href="{{ route('curriculum.show', $curr->id) }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">
                    <i class="fas fa-folder-open"></i> فتح المنهج والدروس
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
            لا يوجد مناهج دراسية مسجلة بعد.
        </div>
    @endforelse
</div>
@endsection

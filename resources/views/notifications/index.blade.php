@extends('layouts.app')
@section('title', 'مركز الإشعارات')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">مركز الإشعارات التنبيهية</h1>
    </div>
    <form action="{{ route('notifications.read_all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-check-double"></i> تحديد الكل كمقروء</button>
    </form>
</div>

<div class="card">
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($notifications as $notif)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: {{ $notif->is_read ? '#ffffff' : '#f0fdf4' }};">
                <div>
                    <div style="font-weight: 800; font-size: 15px; color: var(--primary-dark);">{{ $notif->title }}</div>
                    <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">{{ $notif->message }}</div>
                    <div style="font-size: 11px; color: var(--text-light); margin-top: 4px;">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notif->is_read)
                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-check"></i> قراءة</button>
                    </form>
                @endif
            </div>
        @empty
            <div style="text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد إشعارات حالياً.</div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

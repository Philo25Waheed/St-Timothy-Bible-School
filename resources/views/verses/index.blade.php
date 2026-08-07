@extends('layouts.app')
@section('title', 'مكتبة آيات الحفظ')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">مكتبة آيات الحفظ والكتاب المقدس</h1>
        <p class="page-subtitle">قائمة الآيات المقررة وشواهدها ومتابعة التسميع</p>
    </div>
</div>

<div class="grid grid-cols-3">
    @if(Auth::user()->isAdmin() || Auth::user()->isServant())
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-plus"></i> إضافة آية جديدة للمكتبة</h3>
            <form action="{{ route('verses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">نص الآية</label>
                    <textarea name="text" class="form-control" rows="3" required placeholder="«الرَّبُّ يَقَاتِلُ عَنْكُمْ وَأَنْتُمْ تَصْمُتُونَ»"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">الشاهد (سفر والإصحاح والآية)</label>
                    <input type="text" name="reference" class="form-control" placeholder="خر 14: 14" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;"><i class="fas fa-plus"></i> حفظ الآية</button>
            </form>
        </div>
    @endif

    <div style="grid-column: {{ (Auth::user()->isAdmin() || Auth::user()->isServant()) ? 'span 2' : 'span 3' }};">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 16px;"><i class="fas fa-quote-right" style="color: var(--accent);"></i> آيات الحفظ المقررة</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($verses as $verse)
                    <div style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-right: 5px solid var(--primary); padding: 20px; border-radius: var(--radius-sm);">
                        <div style="font-size: 16px; font-weight: 700; color: var(--primary-dark); line-height: 1.8; font-style: italic;">
                            «{{ $verse->text }}»
                        </div>
                        <div style="font-size: 13px; font-weight: 800; color: var(--accent); margin-top: 8px;">
                            ({{ $verse->reference }})
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 40px;">لا يوجد آيات مسجلة حالياً.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

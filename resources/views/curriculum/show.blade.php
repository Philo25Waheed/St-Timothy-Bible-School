@extends('layouts.app')
@section('title', $curriculum->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $curriculum->title }}</h1>
        <p class="page-subtitle">{{ $curriculum->stage->name ?? '' }} - {{ $curriculum->grade->name ?? '' }}</p>
    </div>
    <a href="{{ route('curriculum.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للمناهج</a>
</div>

<div class="grid grid-cols-3">
    <!-- Left Column: Add Units & Lessons (For Admin) -->
    @if(Auth::user()->isAdmin())
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-plus-circle"></i> إضافة وحدة دراسية جديدة</h3>
        <form action="{{ route('curriculum.units.store', $curriculum->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">عنوان الوحدة</label>
                <input type="text" name="title" class="form-control" required placeholder="الوحدة الأولى: حياة الإيمان">
            </div>
            <div class="form-group">
                <label class="form-label">الفصل الدراسي</label>
                <select name="term" class="form-control">
                    <option value="1">الفصل الدراسي الأول</option>
                    <option value="2">الفصل الدراسي الثاني</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> إضافة الوحدة</button>
        </form>
    </div>
    @endif

    <!-- Units and Lessons Tree -->
    <div style="grid-column: {{ Auth::user()->isAdmin() ? 'span 2' : 'span 3' }};">
        @forelse($curriculum->units as $unit)
            <div class="card" style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <div>
                        <span class="badge badge-warning">ترم {{ $unit->term }}</span>
                        <h3 style="font-size: 18px; font-weight: 800; display: inline-block; margin-right: 8px;">{{ $unit->title }}</h3>
                    </div>
                </div>

                <!-- Lessons inside this unit -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @forelse($unit->lessons as $lesson)
                        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-weight: 700; font-size: 15px; color: var(--primary-dark);">
                                    <i class="fas fa-book-open" style="color: var(--primary-light); margin-left: 8px;"></i>
                                    {{ $lesson->title }}
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                    آية الدرس: {{ $lesson->bible_verse }}
                                </div>
                            </div>
                            <a href="{{ route('lessons.show', $lesson->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> قراءة الدرس
                            </a>
                        </div>
                    @empty
                        <div style="color: var(--text-muted); font-size: 13px;">لا يوجد دروس في هذه الوحدة بعد.</div>
                    @endforelse
                </div>

                <!-- Add Lesson Form inside unit (for admin) -->
                @if(Auth::user()->isAdmin())
                    <hr style="margin: 20px 0; border-top: 1px solid var(--border-color);">
                    <form action="{{ route('units.lessons.store', $unit->id) }}" method="POST" style="background: #fffdf5; padding: 16px; border-radius: var(--radius-sm); border: 1px dashed var(--accent);">
                        @csrf
                        <div style="font-size: 13px; font-weight: 700; color: var(--accent); margin-bottom: 10px;">
                            <i class="fas fa-plus"></i> إضافة درس جديد إلى "{{ $unit->title }}"
                        </div>
                        <div class="grid grid-cols-2">
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" placeholder="عنوان الدرس" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="bible_verse" class="form-control" placeholder="شاهد آية الدرس (مثال: تك 12: 1)">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="memory_verse" class="form-control" placeholder="نص الآية للحفظ">
                        </div>
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="3" placeholder="محتوى وتفاصيل الدرس الشاملة..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-accent btn-sm"><i class="fas fa-save"></i> نشر الدرس</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="card" style="text-align: center; color: var(--text-muted); padding: 40px;">
                لم يتم إضافة وحدات دراسية في هذا المنهج بعد.
            </div>
        @endforelse
    </div>
</div>
@endsection

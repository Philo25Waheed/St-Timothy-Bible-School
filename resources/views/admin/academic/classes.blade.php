@extends('layouts.app')
@section('title', 'الفصول الدراسية وتعيين الخدام')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة الفصول وتعيين الخدام المسؤولين</h1>
        <p class="page-subtitle">ربط خادم أو أكثر بالفصول الدراسية لتحديد الصلاحيات والمتابعة</p>
    </div>
</div>

<div class="grid grid-cols-3">
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-plus"></i> إنشاء فصل جديد وتعيين الخدام</h3>
        <form action="{{ route('academic.classes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">الصف الدراسي</label>
                <select name="grade_id" class="form-control" required>
                    @foreach($grades as $grd)
                        <option value="{{ $grd->id }}">{{ $grd->name }} ({{ $grd->stage->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">اسم الفصل</label>
                <input type="text" name="name" class="form-control" placeholder="فصل القديس مارمرقس" required>
            </div>
            <div class="form-group">
                <label class="form-label">القاعة / الغرفة</label>
                <input type="text" name="room" class="form-control" placeholder="قاعة 101">
            </div>
            <div class="form-group">
                <label class="form-label">تعيين الخدام المسؤولين (يمكن اختيار أكثر من خادم)</label>
                <div style="max-height: 150px; overflow-y: auto; border: 1px solid var(--border-color); padding: 10px; border-radius: var(--radius-sm);">
                    @foreach($servants as $srv)
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                            <input type="checkbox" name="servant_ids[]" value="{{ $srv->id }}">
                            {{ $srv->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;"><i class="fas fa-floppy-disk"></i> إنشاء الفصل وتعيين الخدام</button>
        </form>
    </div>

    <div class="card" style="grid-column: span 2;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;"><i class="fas fa-school"></i> قائمة الفصول والخدام المسندين</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم الفصل</th>
                        <th>الصف والمرحلة</th>
                        <th>الخدام المسؤولون</th>
                        <th>عدد الطلاب</th>
                        <th>إدارة وتعديل خدام الفصل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $cls)
                        <tr>
                            <td style="font-weight: 800; color: var(--primary-dark);">{{ $cls->name }}</td>
                            <td>{{ $cls->grade->name ?? '-' }} ({{ $cls->grade->stage->name ?? '' }})</td>
                            <td>
                                @forelse($cls->servants as $srv)
                                    <span class="badge badge-warning" style="font-size: 12px; margin-bottom: 3px; display: inline-block;">
                                        <i class="fas fa-user-tie"></i> {{ $srv->name }}
                                    </span>
                                @empty
                                    <span class="badge badge-danger">غير مسند لخادِم</span>
                                @endforelse
                            </td>
                            <td><span class="badge badge-success">{{ $cls->students_count }} طالب</span></td>
                            <td>
                                <form action="{{ route('academic.classes.update', $cls->id) }}" method="POST">
                                    @csrf
                                    <div style="max-height: 100px; overflow-y: auto; border: 1px solid var(--border-color); padding: 6px; border-radius: 4px; margin-bottom: 6px;">
                                        @foreach($servants as $srv)
                                            <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; margin-bottom: 2px; cursor: pointer;">
                                                <input type="checkbox" name="servant_ids[]" value="{{ $srv->id }}" {{ $cls->servants->contains($srv->id) ? 'checked' : '' }}>
                                                {{ $srv->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center; font-size: 11px; padding: 4px;" title="حفظ تعيين الخدام">
                                        <i class="fas fa-check"></i> حفظ خدام الفصل
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

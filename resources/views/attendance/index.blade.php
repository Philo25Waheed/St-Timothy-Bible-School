@extends('layouts.app')
@section('title', 'تسجيل الحضور والغياب اليومي')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">تسجيل الحضور والغياب اليومي</h1>
        <p class="page-subtitle">اختر الفصل والتاريخ لتسجيل حضور الطلاب</p>
    </div>
</div>

<!-- Class & Date Filter Bar -->
<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <form action="{{ route('attendance.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label">اختر الفصل الدراسي</label>
            <select name="class_id" class="form-control" onchange="this.form.submit()">
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                        {{ $cls->name }} ({{ $cls->grade->name ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div style="width: 200px;">
            <label class="form-label">التاريخ</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>
</div>

@if($selectedClassId && count($students) > 0)
<div class="card">
    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 800; color: var(--primary-dark);">قائمة الطلاب للتسجيل ({{ count($students) }} طالب)</h3>
            <button type="button" onclick="markAllPresent()" class="btn btn-outline btn-sm"><i class="fas fa-check-double"></i> تحديد الكل حاضر</button>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم الطالب</th>
                        <th>حالة الحضور اليوم</th>
                        <th>ملاحظات خادم الفصل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $st)
                        @php
                            $recStatus = $existingRecords[$st->id]->status ?? 'present';
                            $recNotes = $existingRecords[$st->id]->notes ?? '';
                        @endphp
                        <tr>
                            <td><code>{{ $st->code }}</code></td>
                            <td style="font-weight: 700;">{{ $st->user->name }}</td>
                            <td>
                                <div style="display: flex; gap: 14px; align-items: center;">
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px; font-size: 13px;">
                                        <input type="radio" class="att-radio-present" name="attendance[{{ $st->id }}]" value="present" {{ $recStatus === 'present' ? 'checked' : '' }}>
                                        <span class="badge badge-success">حاضر</span>
                                    </label>

                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px; font-size: 13px;">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="late" {{ $recStatus === 'late' ? 'checked' : '' }}>
                                        <span class="badge badge-warning">متأخر</span>
                                    </label>

                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px; font-size: 13px;">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="absent" {{ $recStatus === 'absent' ? 'checked' : '' }}>
                                        <span class="badge badge-danger">غائب</span>
                                    </label>

                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px; font-size: 13px;">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="excused" {{ $recStatus === 'excused' ? 'checked' : '' }}>
                                        <span class="badge badge-info">بعذر</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="notes[{{ $st->id }}]" class="form-control" style="padding: 6px 10px; font-size: 12px;" placeholder="ملاحظة..." value="{{ $recNotes }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px; text-align: left;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                <i class="fas fa-floppy-disk"></i> حفظ الحضور والغياب ✓
            </button>
        </div>
    </form>
</div>
@elseif($selectedClassId)
<div class="card" style="text-align: center; color: var(--text-muted); padding: 40px;">
    لا يوجد طلاب مسجلون في هذا الفصل حتى الآن.
</div>
@endif

<script>
function markAllPresent() {
    document.querySelectorAll('.att-radio-present').forEach(function(radio) {
        radio.checked = true;
    });
}
</script>
@endsection

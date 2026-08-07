@extends('layouts.app')
@section('title', 'إدارة الطلاب')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة الطلاب</h1>
        <p class="page-subtitle">عرض وإضافة وتعديل بيانات الطلاب المسجلين</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> إضافة طالب جديد</a>
</div>

<!-- Search & Filter Card -->
<div class="card" style="padding: 16px 24px; margin-bottom: 24px;">
    <form action="{{ route('students.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" class="form-control" placeholder="بحث باسم الطالب، الكود، البريد..." value="{{ request('search') }}">
        </div>
        <div style="width: 200px;">
            <select name="stage_id" class="form-control">
                <option value="">كل المراحل</option>
                @foreach($stages as $stg)
                    <option value="{{ $stg->id }}" {{ request('stage_id') == $stg->id ? 'selected' : '' }}>{{ $stg->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> بحث</button>
        <a href="{{ route('students.index') }}" class="btn btn-outline"><i class="fas fa-rotate-left"></i> إعادة ضبط</a>
    </form>
</div>

<!-- Students Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>الطالب</th>
                    <th>المرحلة والصف</th>
                    <th>الفصل</th>
                    <th>ولي الأمر</th>
                    <th>نسبة الحضور</th>
                    <th>المتوسط</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><code>{{ $student->code }}</code></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $student->user->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%;">
                                <div>
                                    <div style="font-weight: 700;">{{ $student->user->name }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $student->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->stage->name ?? '-' }}<br><span style="font-size: 11px; color: var(--text-muted);">{{ $student->grade->name ?? '' }}</span></td>
                        <td><span class="badge badge-info">{{ $student->schoolClass->name ?? '-' }}</span></td>
                        <td>{{ $student->parentUser->name ?? '-' }}</td>
                        <td><span class="badge badge-success">{{ $student->attendance_rate }}%</span></td>
                        <td><span class="badge badge-warning">{{ $student->average_grade }}%</span></td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline btn-sm" title="عرض الملف المفصل"><i class="fas fa-eye"></i> Profile</a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline btn-sm" title="تعديل"><i class="fas fa-pen-to-square"></i></a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('هل أنت تأكد من حذف هذا الطالب؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">لا يوجد طلاب مطابقون لشروط البحث.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $students->links() }}
    </div>
</div>
@endsection

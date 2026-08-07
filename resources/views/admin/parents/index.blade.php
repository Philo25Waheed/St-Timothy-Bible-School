@extends('layouts.app')
@section('title', 'إدارة أولياء الأمور')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة أولياء الأمور</h1>
        <p class="page-subtitle">ربط الأبناء بأولياء الأمور لتسهيل المتابعة المنزلية</p>
    </div>
    <a href="{{ route('parents.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> إضافة ولي أمر جديد</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ولي الأمر</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الأبناء المرتبطون</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $parent)
                    <tr>
                        <td style="font-weight: 700;">
                            <img src="{{ $parent->avatar_url }}" style="width: 32px; height: 32px; border-radius: 50%; vertical-align: middle; margin-left: 8px;">
                            {{ $parent->name }}
                        </td>
                        <td>{{ $parent->email }}</td>
                        <td>{{ $parent->phone ?? '-' }}</td>
                        <td>
                            @forelse($parent->children as $child)
                                <span class="badge badge-success" style="margin-left: 4px;">{{ $child->user->name }} ({{ $child->grade->name ?? '' }})</span>
                            @empty
                                <span class="badge badge-warning">لم يتم ربط أبناء بعد</span>
                            @endforelse
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen-to-square"></i></a>
                                <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('حذف ولي الأمر؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">لا يوجد أولياء أمور مسجلين.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $parents->links() }}
    </div>
</div>
@endsection

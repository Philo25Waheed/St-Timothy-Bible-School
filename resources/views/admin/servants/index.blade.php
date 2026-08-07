@extends('layouts.app')
@section('title', 'إدارة الخدام')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة الخدام</h1>
        <p class="page-subtitle">قائمة خدام فصول مدرسة الكتاب المقدس والأنشطة المسندة</p>
    </div>
    <a href="{{ route('servants.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> إضافة خادم جديد</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الخادم</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الفصول المسندة</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servants as $servant)
                    <tr>
                        <td style="font-weight: 700;">
                            <img src="{{ $servant->avatar_url }}" style="width: 32px; height: 32px; border-radius: 50%; vertical-align: middle; margin-left: 8px;">
                            {{ $servant->name }}
                        </td>
                        <td>{{ $servant->email }}</td>
                        <td>{{ $servant->phone ?? '-' }}</td>
                        <td>
                            @forelse($servant->assignedClasses as $cls)
                                <span class="badge badge-info">{{ $cls->name }}</span>
                            @empty
                                <span class="badge badge-warning">غير مسند لفصل</span>
                            @endforelse
                        </td>
                        <td><span class="badge badge-success">مفعل</span></td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('servants.edit', $servant->id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen-to-square"></i></a>
                                <form action="{{ route('servants.destroy', $servant->id) }}" method="POST" onsubmit="return confirm('تأكيد حذف الخادم؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">لا يوجد خدام حالياً.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $servants->links() }}
    </div>
</div>
@endsection

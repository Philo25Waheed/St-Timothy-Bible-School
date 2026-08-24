@extends('layouts.app')
@section('title', 'إدارة مسئولي النظام')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-user-shield" style="color: var(--primary);"></i> إدارة مسئولي النظام (Admins)</h1>
        <p class="page-subtitle">إدارة حسابات المسئولين وصلاحيات الإدارة العامة للنظام</p>
    </div>
    <a href="{{ route('admins.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة مسئول نظام جديد
    </a>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-2" style="margin-bottom: 24px; max-width: 600px;">
    <div class="stat-card">
        <div>
            <div class="stat-title">إجمالي مسئولي النظام</div>
            <div class="stat-value">{{ $totalAdmins }}</div>
        </div>
        <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-title">الحسابات النشطة</div>
            <div class="stat-value">{{ $activeAdmins }}</div>
        </div>
        <div class="stat-icon" style="color: var(--success);"><i class="fas fa-shield-check"></i></div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>المسئول</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>النوع</th>
                    <th>تاريخ التسجيل</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $adminUser)
                    <tr>
                        <td style="font-weight: 700;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $adminUser->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-light);">
                                <div>
                                    <div>{{ $adminUser->name }}</div>
                                    @if($adminUser->id === Auth::id())
                                        <span class="badge badge-accent" style="font-size: 10px; padding: 2px 6px;">حسابك الحالي (أنت)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <code>{{ $adminUser->email }}</code>
                        </td>
                        <td>{{ $adminUser->phone ?? '-' }}</td>
                        <td>
                            @if($adminUser->gender === 'female')
                                <span style="color: #ec4899;"><i class="fas fa-venus"></i> أنثى</span>
                            @else
                                <span style="color: #3b82f6;"><i class="fas fa-mars"></i> ذكر</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $adminUser->created_at ? $adminUser->created_at->format('Y-m-d') : '-' }}
                        </td>
                        <td>
                            @if($adminUser->is_active)
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> مفعل</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-ban"></i> غير مفعل</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('admins.edit', $adminUser->id) }}" class="btn btn-outline btn-sm" title="تعديل البيانات">
                                    <i class="fas fa-pen-to-square"></i> تعديل
                                </a>

                                @if($adminUser->id !== Auth::id())
                                    <form action="{{ route('admins.destroy', $adminUser->id) }}" method="POST" onsubmit="return confirm('⚠️ هل أنت متأكد من رغبتك في حذف مسئول النظام ({{ $adminUser->name }})؟ هذا الإجراء نهائي.');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="حذف المسئول">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm" disabled style="opacity: 0.5; cursor: not-allowed; background: var(--border-color); color: var(--text-muted);" title="لا يمكن حذف الحساب المسجل به حالياً">
                                        <i class="fas fa-lock"></i> محمي
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            لا يوجد مسئولي نظام مسجلين حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
        <div style="margin-top: 20px;">
            {{ $admins->links() }}
        </div>
    @endif
</div>
@endsection

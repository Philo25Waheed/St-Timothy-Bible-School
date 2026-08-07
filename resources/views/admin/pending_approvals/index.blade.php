@extends('layouts.app')
@section('title', 'طلبات التسجيل المعلقة')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">طلبات التسجيل الجديدة المعلقة والاعتماد</h1>
        <p class="page-subtitle">مراجعة بيانات المستخدمين الجدد وتفعيل الحسابات وإخطارهم</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الصفة المطلوبة</th>
                    <th>البريد الإلكتروني</th>
                    <th>التليفون والعنوان</th>
                    <th>النوع وتاريخ الميلاد</th>
                    <th>بيانات الأبناء (إن وجد)</th>
                    <th>تاريخ الطلب</th>
                    <th>الإجراءات والقبول</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingUsers as $pUser)
                    <tr>
                        <td style="font-weight: 800;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $pUser->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%;">
                                {{ $pUser->name }}
                            </div>
                        </td>
                        <td>
                            @switch($pUser->role)
                                @case('admin') <span class="badge badge-danger">مسؤول</span> @break
                                @case('servant') <span class="badge badge-warning">خادم</span> @break
                                @case('student') <span class="badge badge-info">طالب</span> @break
                                @case('parent') <span class="badge badge-success">ولي أمر</span> @break
                            @endswitch
                        </td>
                        <td><code>{{ $pUser->email }}</code></td>
                        <td>
                            <div><i class="fas fa-phone" style="font-size: 11px; color: var(--primary);"></i> {{ $pUser->phone ?: '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);"><i class="fas fa-location-dot"></i> {{ $pUser->address ?: '-' }}</div>
                        </td>
                        <td>
                            <div>{{ $pUser->gender === 'female' ? 'أنثى 👩' : 'ذكر 👨' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $pUser->birth_date ? $pUser->birth_date->format('Y-m-d') : '-' }}</div>
                        </td>
                        <td>
                            @if(!empty($pUser->pending_children_info))
                                <div style="font-size: 12px;">
                                    @foreach($pUser->pending_children_info as $cInf)
                                        <div style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-bottom: 2px;">
                                            👦 {{ $cInf['name'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $pUser->created_at->diffForHumans() }}</td>
                        <td>
                            <div style="display: flex; gap: 8px; flex-direction: column;">
                                <form action="{{ route('admin.pending.approve', $pUser->id) }}" method="POST">
                                    @csrf
                                    @if($pUser->isServant())
                                        <div style="margin-bottom: 6px;">
                                            <select name="class_id" class="form-control form-control-sm" style="font-size: 11px; padding: 2px 6px;">
                                                <option value="">اختر الفصل للخدمة (اختياري)</option>
                                                @foreach($classes as $cls)
                                                    <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->grade->name ?? '' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-success btn-sm" style="width: 100%; justify-content: center;"><i class="fas fa-check"></i> اعتماد وتفعيل الحساب</button>
                                </form>
                                <form action="{{ route('admin.pending.reject', $pUser->id) }}" method="POST" onsubmit="return confirm('هل أنت تأكد من رفض وحذف الطلب؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; justify-content: center;"><i class="fas fa-times"></i> رفض الطلب</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fas fa-check-circle" style="font-size: 32px; color: var(--success); display: block; margin-bottom: 10px;"></i>
                            لا يوجد طلبات تسجيل معلقة حالياً. جميع الحسابات مراجعة ومفعلة.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

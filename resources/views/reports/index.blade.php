@extends('layouts.app')
@section('title', 'تقارير النظام والإحصائيات')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">التقارير والإحصائيات الشاملة</h1>
        <p class="page-subtitle">تقارير تفصيلية عن الطلاب، الفصول، الحضور، والأداء في الامتحانات</p>
    </div>
</div>

<div class="grid grid-cols-4">
    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-user-graduate" style="font-size: 40px; color: var(--primary); margin-bottom: 16px;"></i>
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">تقرير طالب شامل</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">عرض تقرير أكاديمي وروحي مفصل لطالب محدد</p>
        <a href="{{ route('reports.student') }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">فتح التقرير</a>
    </div>

    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-school" style="font-size: 40px; color: var(--accent); margin-bottom: 16px;"></i>
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">تقرير أداء الفصل</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">إحصائيات ومتوسط الحضور والدرجات بالفصل</p>
        <a href="{{ route('reports.class') }}" class="btn btn-accent btn-sm" style="width: 100%; justify-content: center;">فتح التقرير</a>
    </div>

    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-calendar-check" style="font-size: 40px; color: var(--success); margin-bottom: 16px;"></i>
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">تقرير الحضور والغياب</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">سجل الحضور والغياب حسب الفترة والصف</p>
        <a href="{{ route('reports.attendance') }}" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center;">فتح التقرير</a>
    </div>

    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-chart-pie" style="font-size: 40px; color: #8b5cf6; margin-bottom: 16px;"></i>
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">تقرير نتائج الاختبارات</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">نسب النجاح والمتوسطات العامة للامتحانات</p>
        <a href="{{ route('reports.exam') }}" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center;">فتح التقرير</a>
    </div>
</div>
@endsection

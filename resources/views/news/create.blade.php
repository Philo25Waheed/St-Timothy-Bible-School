@extends('layouts.app')
@section('title', 'نشر خبر جديد')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">نشر خبر جديد</h1>
    </div>
    <a href="{{ route('news.index') }}" class="btn btn-outline"><i class="fas fa-arrow-right"></i> العودة للأخبار</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('news.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">عنوان الخبر</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">تفاصيل الخبر الكاملة</label>
            <textarea name="content" class="form-control" rows="6" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> نشر الخبر</button>
    </form>
</div>
@endsection

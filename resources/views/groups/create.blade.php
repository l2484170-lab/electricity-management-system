@extends('layouts.app')
@section('title', 'إنشاء مجموعة')
@section('page-title', 'إنشاء مجموعة')

@section('content')
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label">الاسم *</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success">إنشاء المجموعة</button>
            <a href="{{ route('groups.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

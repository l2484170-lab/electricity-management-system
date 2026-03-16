@extends('layouts.app')
@section('title', 'إضافة مصروف')
@section('page-title', 'إضافة مصروف')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الفئة *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- اختر --</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">المبلغ *</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">التاريخ *</label><input type="date" name="expense_date" class="form-control" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">الوصف *</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                    <button type="submit" class="btn btn-success">حفظ</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">إضافة فئة</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('expenses.createCategory') }}">
                    @csrf
                    <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="اسم الفئة" required></div>
                    <div class="mb-3"><input type="text" name="description" class="form-control" placeholder="الوصف (اختياري)"></div>
                    <button type="submit" class="btn btn-primary btn-sm">إنشاء فئة</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

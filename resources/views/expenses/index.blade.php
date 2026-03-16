@extends('layouts.app')
@section('title', 'المصروفات')
@section('page-title', 'المصروفات')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="category_id" class="form-select" style="width:200px;">
            <option value="">جميع الفئات</option>
            @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach
        </select>
        <button class="btn btn-primary">تصفية</button>
    </form>
    <a href="{{ route('expenses.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة مصروف</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>الفئة</th><th>المبلغ</th><th>الوصف</th><th>التاريخ</th></tr></thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td>{{ $exp->id }}</td>
                    <td>{{ $exp->category?->name }}</td>
                    <td>{{ number_format($exp->amount, 2) }}</td>
                    <td>{{ Str::limit($exp->description, 50) }}</td>
                    <td>{{ $exp->expense_date }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">لا توجد مصروفات</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $expenses->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'العملاء')
@section('page-title', 'العملاء')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('customers.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة عميل</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>رقم الهوية</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ $c->national_id }}</td>
                    <td><span class="badge {{ $c->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $c->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                    <td>
                        <a href="{{ route('customers.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('customers.archive', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد أرشفة هذا العميل؟')">
                            @csrf
                            <button class="btn btn-sm btn-danger"><i class="bi bi-archive"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">لا يوجد عملاء</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</div>
@endsection

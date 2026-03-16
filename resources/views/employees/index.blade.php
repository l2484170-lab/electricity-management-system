@extends('layouts.app')
@section('title', 'الموظفين')
@section('page-title', 'الموظفين')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('employees.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة موظف</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>المنصب</th><th>القسم</th><th>الراتب الأساسي</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td>{{ $emp->id }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->phone }}</td>
                    <td>{{ $emp->position }}</td>
                    <td>{{ $emp->department }}</td>
                    <td>{{ number_format($emp->base_salary, 2) }}</td>
                    <td><span class="badge {{ $emp->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $emp->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                    <td>
                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">لا يوجد موظفين</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $employees->links() }}
    </div>
</div>
@endsection

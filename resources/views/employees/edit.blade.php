@extends('layouts.app')
@section('title', 'تعديل موظف')
@section('page-title', 'تعديل موظف')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label">الاسم *</label><input type="text" name="name" class="form-control" value="{{ $employee->name }}" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الهاتف</label><input type="text" name="phone" class="form-control" value="{{ $employee->phone }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">البريد الإلكتروني</label><input type="email" name="email" class="form-control" value="{{ $employee->email }}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">المنصب</label><input type="text" name="position" class="form-control" value="{{ $employee->position }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">القسم</label><input type="text" name="department" class="form-control" value="{{ $employee->department }}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الراتب الأساسي</label><input type="number" step="0.01" name="base_salary" class="form-control" value="{{ $employee->base_salary }}"></div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $employee->is_active ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ !$employee->is_active ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">تحديث</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

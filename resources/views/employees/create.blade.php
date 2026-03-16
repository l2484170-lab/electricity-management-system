@extends('layouts.app')
@section('title', 'إضافة موظف')
@section('page-title', 'إضافة موظف')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label">الاسم *</label><input type="text" name="name" class="form-control" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الهاتف</label><input type="text" name="phone" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label">البريد الإلكتروني</label><input type="email" name="email" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">المنصب</label><input type="text" name="position" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label">القسم</label><input type="text" name="department" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الراتب الأساسي</label><input type="number" step="0.01" name="base_salary" class="form-control" value="0"></div>
                <div class="col-md-6 mb-3"><label class="form-label">تاريخ التعيين</label><input type="date" name="hire_date" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">رقم الهوية</label><input type="text" name="national_id" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label">العنوان</label><input type="text" name="address" class="form-control"></div>
            </div>
            <button type="submit" class="btn btn-success">إضافة الموظف</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

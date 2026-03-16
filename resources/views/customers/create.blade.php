@extends('layouts.app')
@section('title', 'إضافة عميل')
@section('page-title', 'إضافة عميل')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label">الاسم *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الهاتف</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">البريد الإلكتروني</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
            </div>
            <div class="mb-3"><label class="form-label">العنوان</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">رقم الهوية</label><input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">القراءة الافتتاحية</label><input type="number" step="0.01" name="opening_reading" class="form-control" value="{{ old('opening_reading', 0) }}"></div>
            </div>
            <div class="mb-3"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
            <button type="submit" class="btn btn-success">إنشاء عميل</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

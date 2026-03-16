@extends('layouts.app')
@section('title', 'تعديل عميل')
@section('page-title', 'تعديل عميل')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label">الاسم *</label><input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الهاتف</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">البريد الإلكتروني</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}"></div>
            </div>
            <div class="mb-3"><label class="form-label">العنوان</label><textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">رقم الهوية</label><input type="text" name="national_id" class="form-control" value="{{ old('national_id', $customer->national_id) }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">القراءة الافتتاحية</label><input type="number" step="0.01" name="opening_reading" class="form-control" value="{{ old('opening_reading', $customer->opening_reading) }}"></div>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $customer->is_active ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $customer->notes) }}</textarea></div>
            <button type="submit" class="btn btn-warning">تحديث العميل</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

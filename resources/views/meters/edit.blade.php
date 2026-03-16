@extends('layouts.app')
@section('title', 'تعديل عداد')
@section('page-title', 'تعديل عداد')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('meters.update', $meter) }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label">رقم العداد *</label><input type="text" name="meter_number" class="form-control" value="{{ $meter->meter_number }}" required></div>
            <div class="mb-3">
                <label class="form-label">العميل</label>
                <select name="customer_id" class="form-select">
                    <option value="">-- غير مخصص --</option>
                    @foreach($customers as $c)<option value="{{ $c->id }}" {{ $meter->customer_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">النوع</label>
                    <select name="meter_type" class="form-select">
                        <option value="analog" {{ $meter->meter_type == 'analog' ? 'selected' : '' }}>تناظري</option>
                        <option value="digital" {{ $meter->meter_type == 'digital' ? 'selected' : '' }}>رقمي</option>
                        <option value="smart" {{ $meter->meter_type == 'smart' ? 'selected' : '' }}>ذكي</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $meter->status == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ $meter->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="disconnected" {{ $meter->status == 'disconnected' ? 'selected' : '' }}>مفصول</option>
                    </select>
                </div>
            </div>
            <div class="mb-3"><label class="form-label">الموقع</label><input type="text" name="location" class="form-control" value="{{ $meter->location }}"></div>
            <button type="submit" class="btn btn-warning">تحديث العداد</button>
            <a href="{{ route('meters.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

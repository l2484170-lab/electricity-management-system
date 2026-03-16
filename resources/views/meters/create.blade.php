@extends('layouts.app')
@section('title', 'إضافة عداد')
@section('page-title', 'إضافة عداد')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('meters.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label">رقم العداد *</label><input type="text" name="meter_number" class="form-control" required></div>
            <div class="mb-3">
                <label class="form-label">العميل</label>
                <select name="customer_id" class="form-select">
                    <option value="">-- غير مخصص --</option>
                    @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">النوع</label>
                    <select name="meter_type" class="form-select">
                        <option value="analog">تناظري</option><option value="digital">رقمي</option><option value="smart">ذكي</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="active">نشط</option><option value="inactive">غير نشط</option><option value="disconnected">مفصول</option>
                    </select>
                </div>
            </div>
            <div class="mb-3"><label class="form-label">الموقع</label><input type="text" name="location" class="form-control"></div>
            <div class="mb-3"><label class="form-label">تاريخ التركيب</label><input type="date" name="installation_date" class="form-control"></div>
            <button type="submit" class="btn btn-success">إنشاء عداد</button>
            <a href="{{ route('meters.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

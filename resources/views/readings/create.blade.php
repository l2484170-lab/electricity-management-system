@extends('layouts.app')
@section('title', 'إضافة قراءة')
@section('page-title', 'إضافة قراءة عداد')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('readings.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">العميل *</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">-- اختر --</option>
                    @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">العداد *</label>
                <select name="meter_id" class="form-select" required>
                    <option value="">-- اختر --</option>
                    @foreach($meters as $m)<option value="{{ $m->id }}">{{ $m->meter_number }} ({{ $m->customer?->name }})</option>@endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الشهر *</label><input type="month" name="month" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">تاريخ القراءة *</label><input type="date" name="reading_date" class="form-control" required></div>
            </div>
            <div class="mb-3"><label class="form-label">قيمة القراءة *</label><input type="number" step="0.01" name="reading_value" class="form-control" required></div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_opening" value="1" class="form-check-input" id="isOpening">
                <label class="form-check-label" for="isOpening">قراءة افتتاحية</label>
            </div>
            <div class="mb-3"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success">حفظ القراءة</button>
            <a href="{{ route('readings.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

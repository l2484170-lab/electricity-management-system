@extends('layouts.app')
@section('title', 'فاتورة يدوية')
@section('page-title', 'إنشاء فاتورة يدوية')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('invoices.manualStore') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">العميل *</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">-- اختر --</option>
                    @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الشهر *</label><input type="month" name="month" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">المبلغ الإجمالي *</label><input type="number" step="0.01" name="total_amount" class="form-control" required></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">الرسوم الثابتة</label><input type="number" step="0.01" name="fixed_fee" class="form-control" value="0"></div>
                <div class="col-md-6 mb-3"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control"></div>
            </div>
            <div class="mb-3"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success">إنشاء الفاتورة</button>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

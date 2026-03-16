@extends('layouts.app')
@section('title', 'تسجيل دفعة')
@section('page-title', 'تسجيل دفعة')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">الفاتورة *</label>
                <select name="invoice_id" class="form-select" required>
                    <option value="">-- اختر فاتورة --</option>
                    @foreach($invoices as $inv)
                    <option value="{{ $inv->id }}">{{ $inv->invoice_number }} - {{ $inv->customer?->name }} (الرصيد: {{ number_format($inv->balance, 2) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label">المبلغ *</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
            <div class="mb-3">
                <label class="form-label">طريقة الدفع</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">نقدي</option><option value="bank_transfer">تحويل بنكي</option><option value="mobile_payment">دفع إلكتروني</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            <button type="submit" class="btn btn-success">تسجيل الدفعة</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection

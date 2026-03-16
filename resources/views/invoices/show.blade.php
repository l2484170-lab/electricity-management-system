@extends('layouts.app')
@section('title', 'تفاصيل الفاتورة')
@section('page-title', 'الفاتورة: ' . $invoice->invoice_number)

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6>تفاصيل الفاتورة</h6></div>
            <div class="card-body">
                <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>العميل:</strong> {{ $invoice->customer?->name }}</p>
                <p><strong>الشهر:</strong> {{ $invoice->month }}</p>
                <p><strong>الاستهلاك:</strong> {{ number_format($invoice->consumption, 1) }} كيلوواط/ساعة</p>
                <p><strong>سعر الوحدة:</strong> {{ number_format($invoice->unit_price, 2) }}</p>
                <p><strong>مبلغ الاستهلاك:</strong> {{ number_format($invoice->consumption_amount, 2) }}</p>
                <p><strong>الرسوم الثابتة:</strong> {{ number_format($invoice->fixed_fee, 2) }}</p>
                <p><strong>الإجمالي:</strong> {{ number_format($invoice->total_amount, 2) }}</p>
                <p><strong>المدفوع:</strong> {{ number_format($invoice->paid_amount, 2) }}</p>
                <p><strong>الرصيد:</strong> {{ number_format($invoice->balance, 2) }}</p>
                <p><strong>الحالة:</strong> <span class="badge badge-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></p>
                <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date?->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6>المدفوعات</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>رقم الإيصال</th><th>المبلغ</th><th>طريقة الدفع</th><th>التاريخ</th></tr></thead>
                    <tbody>
                        @forelse($invoice->payments as $p)
                        <tr>
                            <td>{{ $p->receipt_number }}</td>
                            <td>{{ number_format($p->amount, 2) }}</td>
                            <td>{{ $p->payment_method }}</td>
                            <td>{{ $p->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted">لا توجد مدفوعات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

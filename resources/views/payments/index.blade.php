@extends('layouts.app')
@section('title', 'المدفوعات')
@section('page-title', 'المدفوعات')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('payments.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> تسجيل دفعة</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>رقم الإيصال</th><th>العميل</th><th>رقم الفاتورة</th><th>المبلغ</th><th>طريقة الدفع</th><th>التاريخ</th></tr></thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td>{{ $p->receipt_number }}</td>
                    <td>{{ $p->customer?->name }}</td>
                    <td>{{ $p->invoice?->invoice_number }}</td>
                    <td>{{ number_format($p->amount, 2) }}</td>
                    <td>{{ $p->payment_method }}</td>
                    <td>{{ $p->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">لا توجد مدفوعات</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $payments->links() }}
    </div>
</div>
@endsection

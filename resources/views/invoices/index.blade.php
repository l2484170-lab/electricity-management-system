@extends('layouts.app')
@section('title', 'الفواتير')
@section('page-title', 'الفواتير')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-2">
        <form class="d-flex gap-2" method="GET">
            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            <select name="status" class="form-select" style="width:140px;">
                <option value="">جميع الحالات</option>
                <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>غير مدفوعة</option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>مدفوعة</option>
                <option value="partial" {{ request('status')=='partial'?'selected':'' }}>مدفوعة جزئياً</option>
                <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>متأخرة</option>
            </select>
            <button class="btn btn-primary">تصفية</button>
        </form>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('invoices.generate') }}" class="d-flex gap-2">
            @csrf
            <input type="month" name="month" class="form-control" required>
            <button class="btn btn-success">إنشاء الفواتير</button>
        </form>
        <a href="{{ route('invoices.manualCreate') }}" class="btn btn-outline-primary">فاتورة يدوية</a>
        <form method="POST" action="{{ route('invoices.checkOverdue') }}">@csrf<button class="btn btn-outline-danger">فحص المتأخرة</button></form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>رقم الفاتورة</th><th>العميل</th><th>الشهر</th><th>الاستهلاك</th><th>الإجمالي</th><th>المدفوع</th><th>الرصيد</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->customer?->name }}</td>
                    <td>{{ $inv->month }}</td>
                    <td>{{ number_format($inv->consumption, 1) }}</td>
                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                    <td>{{ number_format($inv->paid_amount, 2) }}</td>
                    <td>{{ number_format($inv->balance, 2) }}</td>
                    <td><span class="badge badge-{{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                    <td><a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted">لا توجد فواتير</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $invoices->links() }}
    </div>
</div>
@endsection

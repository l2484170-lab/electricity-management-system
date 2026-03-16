@extends('layouts.app')
@section('title', 'تفاصيل العميل')
@section('page-title', 'العميل: ' . $customer->name)

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">معلومات العميل</h6></div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $customer->name }}</p>
                <p><strong>الهاتف:</strong> {{ $customer->phone ?? 'غير متوفر' }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $customer->email ?? 'غير متوفر' }}</p>
                <p><strong>العنوان:</strong> {{ $customer->address ?? 'غير متوفر' }}</p>
                <p><strong>رقم الهوية:</strong> {{ $customer->national_id ?? 'غير متوفر' }}</p>
                <p><strong>القراءة الافتتاحية:</strong> {{ $customer->opening_reading }}</p>
                <p><strong>الحالة:</strong> <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $customer->is_active ? 'نشط' : 'غير نشط' }}</span></p>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">تعديل</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">العدادات</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>رقم العداد</th><th>النوع</th><th>الحالة</th></tr></thead>
                    <tbody>
                        @forelse($customer->meters as $m)
                        <tr><td>{{ $m->meter_number }}</td><td>{{ $m->meter_type }}</td><td>{{ $m->status }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-muted">لا توجد عدادات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">آخر الفواتير</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>رقم الفاتورة</th><th>الشهر</th><th>الإجمالي</th><th>الحالة</th></tr></thead>
                    <tbody>
                        @forelse($customer->invoices->take(10) as $inv)
                        <tr>
                            <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                            <td>{{ $inv->month }}</td>
                            <td>{{ number_format($inv->total_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted">لا توجد فواتير</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

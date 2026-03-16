@extends('layouts.app')
@section('title', 'تفاصيل الموظف')
@section('page-title', 'الموظف: ' . $employee->name)

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $employee->name }}</p>
                <p><strong>الهاتف:</strong> {{ $employee->phone }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $employee->email }}</p>
                <p><strong>المنصب:</strong> {{ $employee->position }}</p>
                <p><strong>القسم:</strong> {{ $employee->department }}</p>
                <p><strong>الراتب الأساسي:</strong> {{ number_format($employee->base_salary, 2) }}</p>
                <p><strong>تاريخ التعيين:</strong> {{ $employee->hire_date }}</p>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">تعديل</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6>سجل الحضور</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>التاريخ</th><th>الحالة</th><th>الحضور</th><th>الانصراف</th></tr></thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td>{{ $att->date }}</td>
                            <td><span class="badge {{ $att->status=='present'?'bg-success':'bg-warning' }}">{{ ucfirst($att->status) }}</span></td>
                            <td>{{ $att->check_in }}</td>
                            <td>{{ $att->check_out }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted">لا توجد سجلات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6>الرواتب</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>الشهر</th><th>الصافي</th><th>مدفوع</th></tr></thead>
                    <tbody>
                        @forelse($salaries as $s)
                        <tr>
                            <td>{{ $s->month }}</td>
                            <td>{{ number_format($s->net_salary, 2) }}</td>
                            <td><span class="badge {{ $s->is_paid?'bg-success':'bg-warning' }}">{{ $s->is_paid?'نعم':'لا' }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-muted">لا توجد رواتب</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

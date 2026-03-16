@extends('layouts.app')
@section('title', 'قراءات العدادات')
@section('page-title', 'قراءات العدادات')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="month" name="month" class="form-control" value="{{ request('month') }}">
        <button class="btn btn-primary">تصفية</button>
    </form>
    <a href="{{ route('readings.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة قراءة</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>الرقم</th><th>العميل</th><th>العداد</th><th>الشهر</th><th>السابقة</th><th>الحالية</th><th>الاستهلاك</th><th>التاريخ</th></tr></thead>
            <tbody>
                @forelse($readings as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->customer?->name }}</td>
                    <td>{{ $r->meter?->meter_number }}</td>
                    <td>{{ $r->month }}</td>
                    <td>{{ number_format($r->previous_reading, 1) }}</td>
                    <td>{{ number_format($r->reading_value, 1) }}</td>
                    <td><strong>{{ number_format($r->consumption, 1) }}</strong></td>
                    <td>{{ $r->reading_date }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">لا توجد قراءات</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $readings->links() }}
    </div>
</div>
@endsection

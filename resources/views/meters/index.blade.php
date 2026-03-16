@extends('layouts.app')
@section('title', 'العدادات')
@section('page-title', 'العدادات')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('meters.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة عداد</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>رقم العداد</th><th>العميل</th><th>النوع</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
            <tbody>
                @forelse($meters as $m)
                <tr>
                    <td>{{ $m->id }}</td>
                    <td>{{ $m->meter_number }}</td>
                    <td>{{ $m->customer?->name ?? 'غير مخصص' }}</td>
                    <td>{{ $m->meter_type }}</td>
                    <td><span class="badge {{ $m->status == 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($m->status) }}</span></td>
                    <td><a href="{{ route('meters.edit', $m) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">لا توجد عدادات</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $meters->links() }}
    </div>
</div>
@endsection

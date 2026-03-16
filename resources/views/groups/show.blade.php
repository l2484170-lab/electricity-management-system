@extends('layouts.app')
@section('title', 'تفاصيل المجموعة')
@section('page-title', 'المجموعة: ' . $group->name)

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">الأعضاء</h6>
                <form method="POST" action="{{ route('groups.addMember', $group) }}" class="d-flex gap-2">
                    @csrf
                    <select name="customer_id" class="form-select form-select-sm" required>
                        <option value="">إضافة عميل...</option>
                        @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                    <button class="btn btn-sm btn-success">إضافة</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>العميل</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        @forelse($members as $m)
                        <tr>
                            <td>{{ $m->customer?->name }}</td>
                            <td>
                                <form method="POST" action="{{ route('groups.removeMember', [$group, $m->customer_id]) }}" onsubmit="return confirm('هل تريد الإزالة؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-muted">لا يوجد أعضاء</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">العدادات المركزية</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>رقم العداد</th><th>الموقع</th></tr></thead>
                    <tbody>
                        @forelse($centralMeters as $cm)
                        <tr><td>{{ $cm->meter_number }}</td><td>{{ $cm->location }}</td></tr>
                        @empty
                        <tr><td colspan="2" class="text-muted">لا توجد عدادات مركزية</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0">حساب الفاقد</h6></div>
            <div class="card-body">
                <form method="GET" action="{{ route('groups.loss', $group) }}" class="d-flex gap-2">
                    <input type="month" name="month" class="form-control" required>
                    <button class="btn btn-primary">حساب</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'الرسائل النصية')
@section('page-title', 'الرسائل النصية')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">القوالب</h6></div>
            <div class="card-body">
                @foreach($templates as $tpl)
                <form method="POST" action="{{ route('sms.updateTemplate', $tpl) }}" class="mb-3 border-bottom pb-3">
                    @csrf @method('PUT')
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $tpl->event_type }}</strong>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $tpl->is_active ? 'checked' : '' }}>
                        </div>
                    </div>
                    <textarea name="template_text" class="form-control mb-2" rows="2">{{ $tpl->template_text }}</textarea>
                    <button class="btn btn-sm btn-primary">تحديث</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">إرسال رسالة يدوية</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('sms.send') }}">
                    @csrf
                    <div class="mb-2"><input type="text" name="phone_number" class="form-control" placeholder="رقم الهاتف" required></div>
                    <div class="mb-2"><input type="text" name="customer_name" class="form-control" placeholder="اسم العميل (اختياري)"></div>
                    <div class="mb-2"><textarea name="message" class="form-control" rows="3" placeholder="الرسالة" required></textarea></div>
                    <button class="btn btn-success">إرسال</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">سجل الرسائل</h6></div>
    <div class="card-body table-responsive">
        <table class="table table-sm table-hover">
            <thead><tr><th>الهاتف</th><th>العميل</th><th>الحدث</th><th>الرسالة</th><th>الحالة</th><th>التاريخ</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->phone_number }}</td>
                    <td>{{ $log->customer_name }}</td>
                    <td>{{ $log->event_type }}</td>
                    <td>{{ Str::limit($log->message, 40) }}</td>
                    <td><span class="badge bg-success">{{ $log->status }}</span></td>
                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-muted text-center">لا توجد سجلات</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</div>
@endsection

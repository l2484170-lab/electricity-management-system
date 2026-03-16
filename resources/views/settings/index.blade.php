@extends('layouts.app')
@section('title', 'الإعدادات')
@section('page-title', 'إعدادات النظام')

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">إعدادات النظام</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @foreach($settings as $s)
                    <div class="mb-3">
                        <label class="form-label"><strong>{{ $s->key }}</strong> <small class="text-muted">{{ $s->description }}</small></label>
                        <input type="text" name="settings[{{ $s->key }}]" class="form-control" value="{{ $s->value }}">
                    </div>
                    @endforeach
                    <button class="btn btn-primary">حفظ الإعدادات</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">المستخدمون</h6></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>اسم المستخدم</th><th>الاسم الكامل</th><th>الدور</th><th>نشط</th></tr></thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $u->username }}</td>
                            <td>{{ $u->full_name }}</td>
                            <td><span class="badge bg-primary">{{ $u->role }}</span></td>
                            <td><span class="badge {{ $u->is_active?'bg-success':'bg-secondary' }}">{{ $u->is_active?'نعم':'لا' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0">إضافة مستخدم</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.createUser') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2"><input type="text" name="username" class="form-control" placeholder="اسم المستخدم" required></div>
                        <div class="col-md-6 mb-2"><input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2"><input type="text" name="full_name" class="form-control" placeholder="الاسم الكامل" required></div>
                        <div class="col-md-6 mb-2"><input type="password" name="password" class="form-control" placeholder="كلمة المرور" required></div>
                    </div>
                    <div class="mb-2">
                        <select name="role" class="form-select" required>
                            <option value="admin">مدير</option>
                            <option value="accountant">محاسب</option>
                            <option value="meter_reader">قارئ عدادات</option>
                            <option value="customer_service">خدمة عملاء</option>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm">إنشاء مستخدم</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

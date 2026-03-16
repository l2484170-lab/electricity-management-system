@extends('layouts.app')
@section('title', 'فاقد الكهرباء')
@section('page-title', 'فاقد الكهرباء: ' . $group->name)

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <h5>حساب الفاقد لشهر {{ $result['month'] }}</h5>
        <table class="table">
            <tr><th>استهلاك العداد المركزي</th><td>{{ number_format($result['central_consumption'], 2) }} كيلوواط/ساعة</td></tr>
            <tr><th>استهلاك العملاء</th><td>{{ number_format($result['customer_consumption'], 2) }} كيلوواط/ساعة</td></tr>
            <tr><th>الفاقد</th><td>{{ number_format($result['loss'], 2) }} كيلوواط/ساعة</td></tr>
            <tr><th>نسبة الفاقد</th><td><span class="badge {{ $result['loss_percentage'] > 10 ? 'bg-danger' : 'bg-success' }}">{{ $result['loss_percentage'] }}%</span></td></tr>
        </table>
        <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">رجوع</a>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'المجموعات')
@section('page-title', 'المجموعات')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('groups.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> إضافة مجموعة</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>الاسم</th><th>الوصف</th><th>الأعضاء</th><th>الإجراءات</th></tr></thead>
            <tbody>
                @forelse($groups as $g)
                <tr>
                    <td>{{ $g->id }}</td>
                    <td>{{ $g->name }}</td>
                    <td>{{ $g->description }}</td>
                    <td>{{ $g->members_count }}</td>
                    <td><a href="{{ route('groups.show', $g) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">لا توجد مجموعات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

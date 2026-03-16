@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="stat-card blue">
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
            <div class="stat-label">إجمالي العملاء</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card green">
            <div class="stat-value">{{ $stats['active_customers'] }}</div>
            <div class="stat-label">العملاء النشطون</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card red">
            <div class="stat-value">{{ $stats['overdue_invoices'] }}</div>
            <div class="stat-label">الفواتير المتأخرة</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card green">
            <div class="stat-value">{{ number_format($stats['monthly_revenue'], 2) }}</div>
            <div class="stat-label">الإيرادات الشهرية</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card yellow">
            <div class="stat-value">{{ number_format($stats['monthly_expenses'], 2) }}</div>
            <div class="stat-label">المصروفات الشهرية</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card blue">
            <div class="stat-value">{{ number_format($stats['total_consumption'], 0) }}</div>
            <div class="stat-label">الاستهلاك (كيلوواط/ساعة)</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">الإيرادات مقابل المصروفات (12 شهر)</h6></div>
            <div class="card-body"><canvas id="revenueChart" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">أعلى المستهلكين</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>العميل</th><th>كيلوواط/ساعة</th></tr></thead>
                    <tbody>
                        @foreach($topConsumers as $tc)
                        <tr><td>{{ $tc['customer_name'] }}</td><td>{{ number_format($tc['consumption'], 1) }}</td></tr>
                        @endforeach
                        @if(empty($topConsumers))
                        <tr><td colspan="2" class="text-muted text-center">لا توجد بيانات</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">الاستهلاك الشهري</h6></div>
            <div class="card-body"><canvas id="consumptionChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">فاقد المجموعات</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>المجموعة</th><th>الفاقد</th><th>%</th></tr></thead>
                    <tbody>
                        @foreach($groupLosses as $gl)
                        <tr>
                            <td>{{ $gl['group_name'] }}</td>
                            <td>{{ number_format($gl['loss'], 1) }} كيلوواط/ساعة</td>
                            <td><span class="badge {{ $gl['loss_percentage'] > 10 ? 'bg-danger' : 'bg-success' }}">{{ $gl['loss_percentage'] }}%</span></td>
                        </tr>
                        @endforeach
                        @if(empty($groupLosses))
                        <tr><td colspan="3" class="text-muted text-center">لا توجد بيانات</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const revData = @json($revenueVsExpenses);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revData.map(d => d.month),
        datasets: [
            { label: 'الإيرادات', data: revData.map(d => d.revenue), backgroundColor: '#38a169' },
            { label: 'المصروفات', data: revData.map(d => d.expenses), backgroundColor: '#e53e3e' }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

const consData = @json($monthlyConsumption);
new Chart(document.getElementById('consumptionChart'), {
    type: 'line',
    data: {
        labels: consData.map(d => d.month),
        datasets: [{ label: 'الاستهلاك (كيلوواط/ساعة)', data: consData.map(d => d.consumption), borderColor: '#3182ce', fill: true, backgroundColor: 'rgba(49,130,206,0.1)' }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush

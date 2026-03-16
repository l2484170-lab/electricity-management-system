<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الكهرباء')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root { --sidebar-width: 260px; }
        body { font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .sidebar {
            position: fixed; top: 0; right: 0; width: var(--sidebar-width); height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            color: #fff; overflow-y: auto; z-index: 1000; transition: all 0.3s;
        }
        .sidebar .brand { padding: 1.2rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand h4 { margin: 0; font-size: 1.1rem; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8); padding: 0.7rem 1.2rem; display: flex;
            align-items: center; gap: 0.7rem; transition: all 0.2s; border-right: 3px solid transparent;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,0.1); border-right-color: #63b3ed;
        }
        .sidebar .nav-link i { font-size: 1.1rem; width: 24px; text-align: center; }
        .main-content { margin-right: var(--sidebar-width); padding: 1.5rem; min-height: 100vh; }
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; padding: 0.8rem 1.2rem; background: #fff;
            border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: #fff; border-radius: 0.5rem; padding: 1.2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-right: 4px solid;
        }
        .stat-card.blue { border-right-color: #3182ce; }
        .stat-card.green { border-right-color: #38a169; }
        .stat-card.red { border-right-color: #e53e3e; }
        .stat-card.yellow { border-right-color: #d69e2e; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; }
        .stat-card .stat-label { color: #718096; font-size: 0.85rem; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 0.5rem; }
        .table th { background: #f7fafc; font-weight: 600; font-size: 0.85rem; }
        .badge-paid { background: #38a169; }
        .badge-unpaid { background: #e53e3e; }
        .badge-partial { background: #d69e2e; }
        .badge-overdue { background: #c53030; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-right: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- القائمة الجانبية -->
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <h4><i class="bi bi-lightning-charge"></i> ن.إ.ك</h4>
            <small>نظام إدارة الكهرباء</small>
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="bi bi-people"></i> العملاء
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('meters*') ? 'active' : '' }}" href="{{ route('meters.index') }}">
                    <i class="bi bi-speedometer"></i> العدادات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('readings*') ? 'active' : '' }}" href="{{ route('readings.index') }}">
                    <i class="bi bi-clipboard-data"></i> قراءات العدادات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('invoices*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                    <i class="bi bi-receipt"></i> الفواتير
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('payments*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                    <i class="bi bi-cash-stack"></i> المدفوعات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('groups*') ? 'active' : '' }}" href="{{ route('groups.index') }}">
                    <i class="bi bi-diagram-3"></i> المجموعات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="bi bi-wallet2"></i> المصروفات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i class="bi bi-person-badge"></i> الموظفين
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('sms*') ? 'active' : '' }}" href="{{ route('sms.index') }}">
                    <i class="bi bi-chat-dots"></i> الرسائل النصية
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="bi bi-gear"></i> الإعدادات
                </a>
            </li>
        </ul>
    </nav>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="d-inline mb-0 me-2">@yield('page-title', 'لوحة التحكم')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted"><i class="bi bi-person-circle"></i> {{ auth()->user()->full_name ?? 'مستخدم' }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-left"></i> تسجيل خروج
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

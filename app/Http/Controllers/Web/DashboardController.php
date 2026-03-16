<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = DashboardService::getStats();
        $revenueVsExpenses = DashboardService::getRevenueVsExpenses();
        $monthlyConsumption = DashboardService::getMonthlyConsumption();
        $topConsumers = DashboardService::getTopConsumers();
        $groupLosses = DashboardService::getGroupLosses();

        return view('dashboard', compact(
            'stats', 'revenueVsExpenses', 'monthlyConsumption', 'topConsumers', 'groupLosses'
        ));
    }
}

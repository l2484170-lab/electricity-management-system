<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'stats' => DashboardService::getStats(),
            'revenue_vs_expenses' => DashboardService::getRevenueVsExpenses(),
            'monthly_consumption' => DashboardService::getMonthlyConsumption(),
            'top_consumers' => DashboardService::getTopConsumers(),
            'group_losses' => DashboardService::getGroupLosses(),
        ]);
    }
}

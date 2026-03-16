<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MeterReading;
use App\Models\Expense;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\CentralMeter;
use App\Models\CentralMeterReading;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public static function getStats(): array
    {
        $currentMonth = now()->format('Y-m');
        $totalCustomers = Customer::where('is_archived', false)->count();
        $activeCustomers = Customer::where('is_active', true)->where('is_archived', false)->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $monthlyRevenue = Payment::whereRaw("strftime('%Y-%m', payment_date) = ?", [$currentMonth])->sum('amount') ?? 0;
        $monthlyExpenses = Expense::whereRaw("strftime('%Y-%m', expense_date) = ?", [$currentMonth])->sum('amount') ?? 0;
        $totalConsumption = MeterReading::where('month', $currentMonth)->sum('consumption') ?? 0;

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'overdue_invoices' => $overdueInvoices,
            'monthly_revenue' => (float) $monthlyRevenue,
            'monthly_expenses' => (float) $monthlyExpenses,
            'total_consumption' => (float) $totalConsumption,
        ];
    }

    public static function getRevenueVsExpenses(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = $date->format('Y-m');
            $rev = Payment::whereRaw("strftime('%Y-%m', payment_date) = ?", [$m])->sum('amount') ?? 0;
            $exp = Expense::whereRaw("strftime('%Y-%m', expense_date) = ?", [$m])->sum('amount') ?? 0;
            $data[] = ['month' => $m, 'revenue' => (float) $rev, 'expenses' => (float) $exp];
        }
        return $data;
    }

    public static function getMonthlyConsumption(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = $date->format('Y-m');
            $cons = MeterReading::where('month', $m)->sum('consumption') ?? 0;
            $data[] = ['month' => $m, 'consumption' => (float) $cons];
        }
        return $data;
    }

    public static function getTopConsumers(): array
    {
        $currentMonth = now()->format('Y-m');
        $topConsumers = MeterReading::select('customer_id', DB::raw('SUM(consumption) as total'))
            ->where('month', $currentMonth)
            ->groupBy('customer_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $result = [];
        foreach ($topConsumers as $tc) {
            $customer = Customer::find($tc->customer_id);
            if ($customer) {
                $result[] = [
                    'customer_id' => $tc->customer_id,
                    'customer_name' => $customer->name,
                    'consumption' => (float) $tc->total,
                ];
            }
        }
        return $result;
    }

    public static function getGroupLosses(): array
    {
        $currentMonth = now()->format('Y-m');
        $groups = Group::all();
        $losses = [];

        foreach ($groups as $group) {
            $centralMeters = CentralMeter::where('group_id', $group->id)->get();
            $centralConsumption = 0.0;
            foreach ($centralMeters as $cm) {
                $reading = CentralMeterReading::where('central_meter_id', $cm->id)
                    ->where('month', $currentMonth)->first();
                if ($reading) { $centralConsumption += $reading->consumption; }
            }

            $memberIds = GroupMember::where('group_id', $group->id)->pluck('customer_id')->toArray();
            $customerConsumption = 0.0;
            if (!empty($memberIds)) {
                $customerConsumption = (float) (MeterReading::whereIn('customer_id', $memberIds)
                    ->where('month', $currentMonth)->sum('consumption') ?? 0);
            }

            $loss = $centralConsumption - $customerConsumption;
            $lossPct = $centralConsumption > 0 ? round(($loss / $centralConsumption) * 100, 2) : 0.0;
            $losses[] = [
                'group_id' => $group->id, 'group_name' => $group->name,
                'loss' => $loss, 'loss_percentage' => $lossPct,
            ];
        }
        return $losses;
    }
}

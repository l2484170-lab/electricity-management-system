<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\MeterReading;
use App\Models\Customer;
use App\Models\SystemSetting;
use Illuminate\Support\Str;

class InvoiceService
{
    public static function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public static function generateFromReadings(string $month, int $userId): array
    {
        $unitPrice = (float) SystemSetting::getValue('unit_price', '0.5');
        $fixedFee = (float) SystemSetting::getValue('fixed_fee', '0');

        $readings = MeterReading::where('month', $month)
            ->where('is_opening', false)
            ->get();

        $generated = 0;
        $skipped = 0;

        foreach ($readings as $reading) {
            $existing = Invoice::where('customer_id', $reading->customer_id)
                ->where('month', $month)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            $consumptionAmount = $reading->consumption * $unitPrice;
            $total = $consumptionAmount + $fixedFee;

            Invoice::create([
                'invoice_number' => self::generateInvoiceNumber(),
                'customer_id' => $reading->customer_id,
                'reading_id' => $reading->id,
                'month' => $month,
                'consumption' => $reading->consumption,
                'unit_price' => $unitPrice,
                'consumption_amount' => $consumptionAmount,
                'fixed_fee' => $fixedFee,
                'total_amount' => $total,
                'balance' => $total,
                'status' => 'unpaid',
                'due_date' => now()->addDays(30),
                'created_by' => $userId,
            ]);

            $customer = Customer::find($reading->customer_id);
            if ($customer) {
                SmsService::sendNotification('invoice_created', $customer, [
                    'customer_name' => $customer->name,
                    'total_amount' => number_format($total, 2),
                    'invoice_month' => $month,
                ]);
            }

            $generated++;
        }

        return ['generated' => $generated, 'skipped' => $skipped];
    }

    public static function checkOverdue(): int
    {
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->get();

        $count = 0;
        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['status' => 'overdue']);
            $customer = Customer::find($invoice->customer_id);
            if ($customer) {
                SmsService::sendNotification('payment_overdue', $customer, [
                    'customer_name' => $customer->name,
                    'total_amount' => number_format($invoice->balance, 2),
                    'invoice_month' => $invoice->month,
                ]);
            }
            $count++;
        }

        return $count;
    }
}

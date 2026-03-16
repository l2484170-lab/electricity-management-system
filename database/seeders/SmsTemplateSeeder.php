<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        SmsTemplate::create([
            'event_type' => 'invoice_created',
            'template_text' => 'عزيزي {{customer_name}}، تم إصدار فاتورة الكهرباء لشهر {{invoice_month}}. المبلغ الإجمالي: {{total_amount}}. يرجى الدفع قبل تاريخ الاستحقاق.',
            'is_active' => true,
        ]);

        SmsTemplate::create([
            'event_type' => 'payment_received',
            'template_text' => 'عزيزي {{customer_name}}، تم استلام دفعتك بمبلغ {{total_amount}} عن شهر {{invoice_month}}. شكراً لك!',
            'is_active' => true,
        ]);

        SmsTemplate::create([
            'event_type' => 'payment_overdue',
            'template_text' => 'عزيزي {{customer_name}}، فاتورتك لشهر {{invoice_month}} متأخرة. الرصيد المستحق: {{total_amount}}. يرجى الدفع في أقرب وقت.',
            'is_active' => true,
        ]);
    }
}

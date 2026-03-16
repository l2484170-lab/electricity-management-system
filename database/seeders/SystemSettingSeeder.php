<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
                        ['key' => 'unit_price', 'value' => '0.50', 'description' => 'سعر وحدة الكيلووات/ساعة'],
                        ['key' => 'fixed_fee', 'value' => '5.00', 'description' => 'الرسم الشهري الثابت لكل عميل'],
                        ['key' => 'station_name', 'value' => 'محطة الكهرباء الرئيسية', 'description' => 'اسم المحطة'],
                        ['key' => 'station_phone', 'value' => '+1234567890', 'description' => 'رقم هاتف المحطة'],
                        ['key' => 'station_address', 'value' => 'الشارع الرئيسي 123', 'description' => 'عنوان المحطة'],
                        ['key' => 'invoice_due_days', 'value' => '30', 'description' => 'عدد الأيام حتى تأخر الفاتورة'],
                        ['key' => 'sms_enabled', 'value' => 'true', 'description' => 'تفعيل إشعارات الرسائل النصية'],
                        ['key' => 'currency', 'value' => 'USD', 'description' => 'رمز العملة'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}

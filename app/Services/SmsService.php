<?php

namespace App\Services;

use App\Models\SmsTemplate;
use App\Models\SmsLog;
use App\Models\Customer;

class SmsService
{
    public static function renderTemplate(string $template, array $variables): string
    {
        $result = $template;
        foreach ($variables as $key => $value) {
            $result = str_replace('{{' . $key . '}}', (string) $value, $result);
        }
        return $result;
    }

    public static function sendNotification(string $eventType, Customer $customer, array $variables): void
    {
        $template = SmsTemplate::where('event_type', $eventType)
            ->where('is_active', true)
            ->first();

        if ($template && $customer->phone) {
            $message = self::renderTemplate($template->template_text, $variables);
            SmsLog::create([
                'phone_number' => $customer->phone,
                'message' => $message,
                'event_type' => $eventType,
                'status' => 'sent',
                'customer_name' => $customer->name,
            ]);
        }
    }
}

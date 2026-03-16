<?php

namespace App\Services;

use Illuminate\Support\Str;

class PaymentService
{
    public static function generateReceiptNumber(): string
    {
        return 'RCP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

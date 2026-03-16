<?php

namespace App\Services;

use App\Models\MeterReading;
use App\Models\Meter;

class ReadingService
{
    public static function getPreviousReading(int $meterId, string $month): float
    {
        $lastReading = MeterReading::where('meter_id', $meterId)
            ->where('month', '<', $month)
            ->orderBy('month', 'desc')
            ->first();

        if ($lastReading) {
            return $lastReading->reading_value;
        }

        $meter = Meter::with('customer')->find($meterId);
        if ($meter && $meter->customer) {
            return $meter->customer->opening_reading ?? 0.0;
        }

        return 0.0;
    }
}

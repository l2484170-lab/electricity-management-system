<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\CentralMeter;
use App\Models\CentralMeterReading;
use App\Models\MeterReading;

class ElectricityLossService
{
    public static function calculateLoss(int $groupId, string $month): array
    {
        $group = Group::findOrFail($groupId);
        $centralMeters = CentralMeter::where('group_id', $groupId)->get();
        $centralConsumption = 0.0;

        foreach ($centralMeters as $cm) {
            $reading = CentralMeterReading::where('central_meter_id', $cm->id)
                ->where('month', $month)->first();
            if ($reading) { $centralConsumption += $reading->consumption; }
        }

        $memberIds = GroupMember::where('group_id', $groupId)->pluck('customer_id')->toArray();
        $customerConsumption = 0.0;
        if (!empty($memberIds)) {
            $customerConsumption = (float) (MeterReading::whereIn('customer_id', $memberIds)
                ->where('month', $month)->sum('consumption') ?? 0);
        }

        $loss = $centralConsumption - $customerConsumption;
        $lossPct = $centralConsumption > 0 ? round(($loss / $centralConsumption) * 100, 2) : 0.0;

        return [
            'group_id' => $groupId, 'group_name' => $group->name, 'month' => $month,
            'central_consumption' => $centralConsumption, 'customer_consumption' => $customerConsumption,
            'loss' => $loss, 'loss_percentage' => $lossPct,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentralMeterReading extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'central_meter_id', 'reading_value', 'previous_reading',
        'consumption', 'month', 'reading_date',
    ];

    protected $casts = [
        'reading_value' => 'double',
        'previous_reading' => 'double',
        'consumption' => 'double',
        'reading_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function centralMeter(): BelongsTo
    {
        return $this->belongsTo(CentralMeter::class);
    }
}

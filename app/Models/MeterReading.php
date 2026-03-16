<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'meter_id', 'customer_id', 'reading_value', 'previous_reading',
        'consumption', 'reading_date', 'month', 'is_opening', 'notes', 'read_by',
    ];

    protected $casts = [
        'reading_value' => 'double',
        'previous_reading' => 'double',
        'consumption' => 'double',
        'reading_date' => 'datetime',
        'is_opening' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}

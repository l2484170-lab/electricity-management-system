<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meter extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_number', 'customer_id', 'status', 'location',
        'meter_type', 'installation_date',
    ];

    protected $casts = [
        'installation_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }
}

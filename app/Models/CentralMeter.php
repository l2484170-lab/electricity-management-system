<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentralMeter extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['meter_number', 'group_id', 'location'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(CentralMeterReading::class);
    }
}

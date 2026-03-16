<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'national_id',
        'opening_reading', 'is_active', 'is_archived', 'notes',
    ];

    protected $casts = [
        'opening_reading' => 'double',
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function meters(): HasMany
    {
        return $this->hasMany(Meter::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }
}

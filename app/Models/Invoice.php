<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'customer_id', 'reading_id', 'month',
        'consumption', 'unit_price', 'consumption_amount', 'fixed_fee',
        'total_amount', 'paid_amount', 'balance', 'status',
        'due_date', 'notes', 'created_by',
    ];

    protected $casts = [
        'consumption' => 'double',
        'unit_price' => 'double',
        'consumption_amount' => 'double',
        'fixed_fee' => 'double',
        'total_amount' => 'double',
        'paid_amount' => 'double',
        'balance' => 'double',
        'due_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reading(): BelongsTo
    {
        return $this->belongsTo(MeterReading::class, 'reading_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

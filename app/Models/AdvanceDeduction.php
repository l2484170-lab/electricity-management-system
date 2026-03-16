<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvanceDeduction extends Model
{
    public $timestamps = false;

    protected $table = 'advances_deductions';

    protected $fillable = [
        'employee_id', 'type', 'amount', 'description', 'date', 'month',
    ];

    protected $casts = [
        'amount' => 'double',
        'date' => 'date',
        'created_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

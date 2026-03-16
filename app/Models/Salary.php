<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'month', 'base_salary', 'deductions',
        'advances', 'bonuses', 'net_salary', 'is_paid', 'paid_date',
    ];

    protected $casts = [
        'base_salary' => 'double',
        'deductions' => 'double',
        'advances' => 'double',
        'bonuses' => 'double',
        'net_salary' => 'double',
        'is_paid' => 'boolean',
        'paid_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

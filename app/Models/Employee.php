<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'position', 'department',
        'base_salary', 'hire_date', 'is_active', 'national_id', 'address',
    ];

    protected $casts = [
        'base_salary' => 'double',
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function advancesDeductions(): HasMany
    {
        return $this->hasMany(AdvanceDeduction::class);
    }
}

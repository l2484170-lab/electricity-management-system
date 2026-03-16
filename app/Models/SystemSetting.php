<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    public $timestamps = false;

    protected $table = 'system_settings';

    protected $fillable = ['key', 'value', 'description'];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public static function getValue(string $key, string $default = '0'): string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

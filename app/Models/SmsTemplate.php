<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $table = 'sms_templates';

    protected $fillable = ['event_type', 'template_text', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

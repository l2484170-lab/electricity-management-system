<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    public $timestamps = false;

    protected $table = 'sms_logs';

    protected $fillable = [
        'phone_number', 'message', 'event_type', 'status', 'customer_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}

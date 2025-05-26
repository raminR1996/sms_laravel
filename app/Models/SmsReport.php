<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsReport extends Model
{
    protected $fillable = ['user_id', 'type', 'line_number', 'numbers', 'message', 'sms_count', 'batch_id', 'statuses', 'datetimes'];

    protected $casts = [
        'numbers'   => 'array',
        'statuses'  => 'array',
        'datetimes' => 'array',
    ];
}
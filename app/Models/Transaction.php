<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'sms_count',
        'amount',
        'description',
        'sms_balance_after',
    ];
}

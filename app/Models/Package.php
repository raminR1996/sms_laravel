<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['sms_count', 'price', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

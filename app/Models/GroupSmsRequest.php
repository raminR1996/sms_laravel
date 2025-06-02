<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSmsRequest extends Model
{
    protected $fillable = [
        'user_id',
        'line_number',
        'message',
        'village_ids',
        'sms_count',
        'status',
        'approved_by',
        'approved_at',
        'batch_id',
        'statuses',
        'datetimes',
    ];

    protected $casts = [
        'village_ids' => 'array',
        'batch_id' => 'array',
        'approved_at' => 'datetime',
        'statuses' => 'array',
        'datetimes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
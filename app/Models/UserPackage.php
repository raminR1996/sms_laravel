<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    protected $fillable = ['user_id', 'package_id', 'payment_id', 'sms_count', 'price'];
    protected $casts = [
    'sms_count' => 'integer',
    'price' => 'integer',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
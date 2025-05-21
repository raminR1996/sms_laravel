<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['village_id', 'mobile_number', 'full_name', 'birth_date', 'gender'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
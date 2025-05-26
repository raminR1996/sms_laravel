<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = ['line_number', 'operator_name', 'line_type', 'is_active'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'profile_completed',
        'documents_verified',
        'sms_balance', // اضافه کردن sms_balance
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sms_balance' => 'integer', // نوع داده برای sms_balance
        ];
    }

    public function documents()
    {
        return $this->hasOne(Document::class);
    }

    // متدهای کمکی برای چک کردن نقش
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
    public function payments()
{
    return $this->hasMany(Payment::class);
}

public function userPackages()
{
    return $this->hasMany(UserPackage::class);
}
}
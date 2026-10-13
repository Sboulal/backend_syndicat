<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // المستخدم عندو شقق (يقدر يكون مالك أو مكتري)
    public function lots()
    {
        return $this->belongsToMany(Lot::class, 'lot_users')
                    ->withPivot('relation_type', 'start_date', 'end_date')
                    ->withTimestamps();
    }

    // المستخدم يقدر يحط بزاف ديال الشكايات
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
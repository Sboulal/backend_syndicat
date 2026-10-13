<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheduled_date',
        'agenda',
        'status',
        'minutes_path',
    ];

    protected $casts = [
        'agenda' => 'array', // باش يحول JSON لـ Array فـ PHP
    ];

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }
}
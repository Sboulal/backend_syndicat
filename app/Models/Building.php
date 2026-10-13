<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'address',
    ];

    // العمارة فيها بزاف ديال الشقق
    public function lots()
    {
        return $this->hasMany(Lot::class);
    }
}

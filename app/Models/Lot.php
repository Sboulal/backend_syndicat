<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id',
        'number',
        'floor',
        'type',
        'quote_part',
    ];

    // الشقة تابعة لعمارة
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // الشقة كيسكنو فيها مستخدمين (ملاك أو مكتريين)
    public function users()
    {
        return $this->belongsToMany(User::class, 'lot_users')
                    ->withPivot('relation_type', 'start_date', 'end_date')
                    ->withTimestamps();
    }

    // الشقة عندها بزاف ديال المساهمات (Appels de fonds)
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}
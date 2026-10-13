<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'supplier_id',
        'subject',
        'scheduled_date',
        'realized_date',
        'priority',
        'status',
        'estimated_cost',
        'real_cost',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // هاد الإصلاح يقدر يحل بزاف د الشكايات ديال السكان
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
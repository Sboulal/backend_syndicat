<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'lot_id',
        'intervention_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
    ];

    // مول الشكاية
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الشقة المعنية
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    // الإصلاح اللي غيحل المشكل (يلا كان)
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    // الشخص المكلف بالشكاية (بحال Adjoint)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
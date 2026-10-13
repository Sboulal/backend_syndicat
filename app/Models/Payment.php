<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contribution_id',
        'amount_paid',
        'payment_date',
        'payment_method', // virement, especes, cheque
        'reference',
        'receipt_number',
        'proof_path',
        'status', // pending_validation, validated, rejected
    ];

    // هاد الأداء تابع لواحد المساهمة
    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}
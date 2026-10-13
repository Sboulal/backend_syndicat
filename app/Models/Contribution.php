<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lot_id',
        'title',
        'amount_due',
        'due_date',
        'status', // paid, partial, unpaid, late
    ];

    // هاد المساهمة تابعة لواحد الشقة
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    // هاد المساهمة يقدر يكونو تخلصو فيها بزاف ديال الدفعات (Paiements)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
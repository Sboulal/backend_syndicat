<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'activity',
        'contact_name',
        'phone',
        'email',
        'address',
    ];

    // المورد كيقدر يعطينا بزاف د الفاتورات (المصاريف)
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // المورد كيقدر يدير بزاف د الإصلاحات
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
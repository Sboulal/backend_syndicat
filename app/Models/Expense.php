<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'description',
        'amount',
        'expense_date',
        'payment_date',
        'payment_method',
        'status', // to_pay, partial, paid
        'invoice_path',
    ];

    // هاد المصروف تابع لواحد الفئة
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    // هاد المصروف يقدر يكون ديال واحد المورد (Supplier)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
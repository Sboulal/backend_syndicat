<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'file_path',
    ];

    // Polymorphic relation: هاد الدوكيمون يقدر يلصق فـ شقة، فاتورة، او شكاية..
    public function documentable()
    {
        return $this->morphTo();
    }
}
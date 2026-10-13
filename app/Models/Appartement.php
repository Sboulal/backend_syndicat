<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
   use HasFactory;

    protected $fillable = ['numero', 'etage', 'bloc', 'user_id'];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}

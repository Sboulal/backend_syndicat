<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['montant', 'mois_concerne', 'date_paiement', 'status', 'preuve_image', 'appartement_id'];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }
}

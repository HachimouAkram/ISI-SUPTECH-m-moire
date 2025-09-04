<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
    use HasFactory;
    protected $fillable = ['fichier_pdf', 'date_emission', 'paiement_id'];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}

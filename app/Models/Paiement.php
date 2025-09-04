<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','montant', 'date', 'mode_paiement', 'type_paiement', 'inscription_id'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function recu()
    {
        return $this->hasOne(Recu::class);
    }
}


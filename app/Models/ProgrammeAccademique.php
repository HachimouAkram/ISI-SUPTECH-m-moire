<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeAccademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_ouverture_inscription',
        'date_fermeture_inscription',
        'annee_accademique',
        'etat',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}

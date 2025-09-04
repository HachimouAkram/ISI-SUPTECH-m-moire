<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = ['prix_inscription', 'prix_mensuel', 'mois_rentree', 'duree', 'niveau', 'formation_id'];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

}


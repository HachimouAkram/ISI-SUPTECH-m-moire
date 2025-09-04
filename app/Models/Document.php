<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'chemin_fichier',
        'inscription_id'
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'duree', 'type_formation', 'domaine'];

    public function classes()
    {
        return $this->hasMany(Classe::class)->orderBy('niveau');
    }

}


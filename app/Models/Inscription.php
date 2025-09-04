<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'statut', 'classe_id', 'user_id', 'programme_accademique_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }


    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'inscription_id');
    }
    public function programmeAccademique()
    {
        return $this->belongsTo(ProgrammeAccademique::class);
    }

}

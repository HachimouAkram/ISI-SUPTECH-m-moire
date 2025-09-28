<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Classe extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['prix_inscription', 'prix_mensuel', 'mois_rentree', 'duree', 'niveau', 'formation_id'];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Configurer les options de logs Spatie
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['prix_inscription', 'prix_mensuel', 'mois_rentree', 'duree', 'niveau', 'formation_id'])
            ->useLogName('classe')
            ->logOnlyDirty() // log uniquement si les colonnes changent
            ->setDescriptionForEvent(function(string $eventName){
                $user = Auth::user()?->name ?? 'Système';
                return match($eventName) {
                    'created' => "Classe ajoutée par $user",
                    'updated' => "Classe modifiée par $user",
                    'deleted' => "Classe supprimée par $user",
                };
            });
    }
}

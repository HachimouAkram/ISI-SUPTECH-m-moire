<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Inscription extends Model
{
    use HasFactory, LogsActivity;

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

    /**
     * Configurer les options de logs Spatie
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['statut']) // on ne log que le changement de statut
            ->useLogName('inscription')
            ->logOnlyDirty() // log uniquement si le statut change
            ->setDescriptionForEvent(function(string $eventName){
                $user = Auth::user()?->name ?? 'Système';

                if ($eventName === 'updated') {
                    return match($this->statut) {
                        'Validée' => "Inscription validée par $user",
                        'Refusée' => "Inscription refusée par $user",
                        default => "Statut mis à jour par $user",
                    };
                }

                return "$eventName sur l'inscription par $user";
            });
    }
}

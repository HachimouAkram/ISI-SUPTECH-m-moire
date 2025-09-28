<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Formation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['nom', 'description', 'duree', 'type_formation', 'domaine'];

    public function classes()
    {
        return $this->hasMany(Classe::class)->orderBy('niveau');
    }

    /**
     * Configurer les options de logs Spatie
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nom', 'description', 'duree', 'type_formation', 'domaine']) // colonnes à suivre
            ->useLogName('formation') // catégorie du log
            ->logOnlyDirty() // ne log que si des colonnes changent
            ->setDescriptionForEvent(function(string $eventName){
                $user = Auth::user()?->name ?? 'Système';
                return match($eventName) {
                    'created' => "Formation ajoutée par $user",
                    'updated' => "Formation modifiée par $user",
                    'deleted' => "Formation supprimée par $user",
                };
            });
    }
}

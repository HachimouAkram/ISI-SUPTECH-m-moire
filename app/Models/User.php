<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'etat', 'nom', 'prenom', 'email', 'password', 'role', 'role_id',
        'telephone', 'sexe', 'date_naissance', 'must_change_password',
        'email_verification_code', 'is_verified','photo','fonction',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function rappel()
    {
        return $this->hasOne(Rappel::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Configurer les options de logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nom', 'prenom', 'email', 'role'])
            ->useLogName('user')
            ->logOnlyDirty()
            ->setDescriptionForEvent(function(string $eventName){
                $actor = Auth::user()?->name ?? 'Système';
                return match($eventName) {
                    'created' => "Utilisateur ajouté par $actor",
                    'updated' => "Utilisateur modifié par $actor",
                    'deleted' => "Utilisateur supprimé par $actor",
                };
            });
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}

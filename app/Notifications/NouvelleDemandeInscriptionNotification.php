<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleDemandeInscriptionNotification extends Notification
{
    use Queueable;

    protected $inscription;

    public function __construct($inscription)
    {
        $this->inscription = $inscription;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouvelle demande d’inscription',
            'message' => 'Une nouvelle demande d’inscription a été effectuée par ' . $this->inscription->user->nom,
            'url' => route('inscriptions.show', $this->inscription->id),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}

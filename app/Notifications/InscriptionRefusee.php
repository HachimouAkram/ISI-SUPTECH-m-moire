<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionRefusee extends Notification
{
    use Queueable;

    protected $motif;

    public function __construct($motif)
    {
        $this->motif = $motif;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Refus de votre inscription')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous sommes désolés, mais votre inscription a été refusée.')
            ->line('Motif : ' . $this->motif) // 👉 affichage du motif
            ->line('Veuillez contacter l’administration pour plus d’informations.')
            ->line('Merci de votre compréhension.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Inscription refusée',
            'message' => 'Votre inscription a été refusée. Motif : ' . $this->motif,
            'url' => route('contact')
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Inscription refusée',
            'message' => 'Votre inscription a été refusée. Motif : ' . $this->motif,
            'url' => route('contact')
        ];
    }
}


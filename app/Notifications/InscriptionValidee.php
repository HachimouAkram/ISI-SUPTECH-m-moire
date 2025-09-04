<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionValidee extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    /*public function via(object $notifiable): array
    {
        return ['mail'];
    }*/

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre inscription')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous confirmons que votre inscription a bien été enregistrée.')
            ->line('Pour finaliser votre dossier, merci de procéder au paiement dès que possible.')
            ->action('Accéder au paiement', url(route('paiement.choix')))
            ->line('Merci de votre confiance et bienvenue parmi nous !');
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Inscription validée',
            'message' => 'Votre inscription a été validée. Veuillez procéder au paiement.',
            'url' => route('paiement.index')
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

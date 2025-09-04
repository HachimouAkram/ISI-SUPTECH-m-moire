<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RecuPaiementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;
    public $user;
    public $filePath;

    public function __construct($paiement, $user, $filePath)
    {
        $this->paiement = $paiement;
        $this->user = $user;
        $this->filePath = $filePath;
    }


    public function build()
    {
        return $this->subject('Votre reçu de paiement')
                    ->view('emails.recu')
                    ->attachData(Storage::get('public/'.$this->filePath), 'recu.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}

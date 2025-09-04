<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RappelDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $messageRappel;

    public function __construct($user, $messageRappel)
    {
        $this->user = $user;
        $this->messageRappel = $messageRappel;
    }

    public function build()
    {
        return $this->subject("Rappel de documents manquants")
                    ->view('emails.rappel_document');
    }
}

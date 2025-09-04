<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Inscription;

class AttestationInscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $inscription;
    public $attestationPath;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Inscription $inscription, string $attestationPath)
    {
        $this->user = $user;
        $this->inscription = $inscription;
        $this->attestationPath = $attestationPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Attestation d\'Inscription - ' . $this->inscription->classe->formation->nom)
                    ->view('emails.attestation-inscription')
                    ->with([
                        'user' => $this->user,
                        'inscription' => $this->inscription,
                    ])
                    ->attachData(
                        Storage::get('public/' . $this->attestationPath),
                        'Attestation_Inscription_' . $this->user->nom . '_' . $this->user->prenom . '.pdf',
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
    }
}

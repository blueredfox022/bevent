<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Participant $participant;
    public string $qrUrl;

    public function __construct(
        Participant $participant,
        string $qrUrl
    ) {
        $this->participant = $participant;
        $this->qrUrl = $qrUrl;
    }

    public function build()
    {
        return $this
            ->subject('Registrasi Event Berhasil')
            ->view('emails.registration')
            ->with([
                'participant' => $this->participant,
                'qrUrl' => $this->qrUrl,
            ]);
    }
}

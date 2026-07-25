<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $qrPath;

    public function __construct(
        Participant $participant,
        string $qrPath
    ) {
        $this->participant = $participant;
        $this->qrPath = $qrPath;
    }

    public function build()
    {
        return $this
            ->subject('Registrasi Event Berhasil')
            ->view('emails.registration');
    }
}

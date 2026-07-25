<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Participant;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $pdfContent;

    public function __construct(Participant $participant, $pdfContent)
    {
        $this->participant = $participant;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Sertifikat Event ' . $this->participant->event->title)
            ->view('emails.certificate')
            ->attachData(
                $this->pdfContent,
                'sertifikat-' . $this->participant->name . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}

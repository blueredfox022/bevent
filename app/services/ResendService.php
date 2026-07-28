<?php

namespace App\Services;

use Resend;

class ResendService
{
    protected $resend;

    public function __construct()
    {
        $this->resend = Resend::client(env('RESEND_API_KEY'));
    }

    public function send($to, $subject, $html)
    {
        return $this->resend->emails->send([
            'from' => 'Event Web <onboarding@resend.dev>',
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ]);
    }
}

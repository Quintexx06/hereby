<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The couple's welcome when the team set up their wedding: one link to
 * choose a password and step in.
 */
class CoupleInvitation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $couple, public string $link) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "{$this->couple}, eure Hochzeitswebsite wartet");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.couple-invitation');
    }
}

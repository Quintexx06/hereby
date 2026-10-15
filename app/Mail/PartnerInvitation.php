<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A venue's, photographer's or planner's welcome: one link to choose a
 * password and see the weddings their couples connect.
 */
class PartnerInvitation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $partner, public string $link) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "{$this->partner}: euer Zugang zu Hereby");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.partner-invitation');
    }
}

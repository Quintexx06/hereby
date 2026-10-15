<?php

namespace App\Mail;

use App\Models\DesignBrief;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A couple sent their brief for «Massanfertigung»: the team's to-do.
 */
class BespokeBriefSubmitted extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public DesignBrief $brief) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Massanfertigung: {$this->brief->wedding->couple_names}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.bespoke-brief-submitted', with: ['link' => route('admin.briefs.index')]);
    }
}

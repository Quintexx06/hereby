<?php

namespace App\Mail;

use App\Models\PartnerInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A couple invites their venue, photographer or planner: one link to open
 * a Hereby account that already sees their wedding.
 */
class PartnerJoinInvitation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public PartnerInvite $invite) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "{$this->invite->wedding->couple_names} laden euch zu Hereby ein");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.partner-join-invitation', with: [
            'couple' => $this->invite->wedding->couple_names,
            'sees' => $this->invite->type->seesFinalNumbers()
                ? 'Gästezahlen, Menüs und Allergien pro Tisch, immer aktuell, ohne Listen per Mail'
                : 'einen Ort, an dem ihr eure Fotos direkt in ihre «Erinnerungen» legt',
            'link' => route('partner-join.show', $this->invite->token),
            'days' => PartnerInvite::DAYS_VALID,
        ]);
    }
}

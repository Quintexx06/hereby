<?php

namespace App\Mail;

use App\Models\Household;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * «Heimweg»: half an hour before one of the night's last connections,
 * from the couple by name (spec 2026-10-13).
 */
class WayHomeReminder extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @param  array{label: string, place: string|null, time: string}  $departure
     */
    public function __construct(public Household $household, public array $departure) {}

    public function envelope(): Envelope
    {
        $couple = $this->household->wedding->couple_names;

        return new Envelope(
            from: new Address((string) config('mail.from.address'), "{$couple} via Hereby"),
            subject: __('evening.way_home.subject', ['label' => $this->departure['label'], 'time' => $this->departure['time']], $this->locale),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.way-home-reminder', with: [
            'couple' => $this->household->wedding->couple_names,
            'departure' => $this->departure,
            'link' => route('invitation.show', $this->household),
        ]);
    }
}

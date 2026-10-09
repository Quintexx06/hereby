<?php

namespace App\Mail;

use App\Models\Household;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

/**
 * A friendly nudge before the deadline, from the couple by name, with the
 * household's own link and a one-click way to stop (roadmap 1.10).
 */
class GuestReminder extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Household $household) {}

    public function envelope(): Envelope
    {
        $couple = $this->household->wedding->couple_names;

        return new Envelope(
            from: new Address((string) config('mail.from.address'), "{$couple} via Hereby"),
            subject: __('rsvp.reminder.subject', ['couple' => $couple, 'date' => $this->deadline()], $this->locale),
        );
    }

    public function headers(): Headers
    {
        return new Headers(text: [
            'List-Unsubscribe' => '<'.$this->stopUrl().'>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.guest-reminder', with: [
            'couple' => $this->household->wedding->couple_names,
            'deadline' => $this->deadline(),
            'link' => route('invitation.show', $this->household),
            'stop' => $this->stopUrl(),
        ]);
    }

    private function deadline(): string
    {
        return (string) $this->household->wedding->rsvp_deadline?->copy()
            ->settings(['locale' => $this->locale])->isoFormat('LL');
    }

    private function stopUrl(): string
    {
        return URL::signedRoute('invitation.reminders.stop', $this->household);
    }
}

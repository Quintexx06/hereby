<?php

namespace App\Mail;

use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Support\WeddingCalendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The guest's proof of their answer, from the couple by name (so it never
 * reads like phishing), in the household's language. Never carries allergies.
 */
class ReplyConfirmation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Household $household) {}

    public function envelope(): Envelope
    {
        $couple = $this->household->wedding->couple_names;

        return new Envelope(
            from: new Address((string) config('mail.from.address'), "{$couple} via Hereby"),
            subject: __('rsvp.mail.subject', ['couple' => $couple], $this->locale),
        );
    }

    public function content(): Content
    {
        $household = $this->household->loadMissing(['wedding', 'events', 'guests.responses']);
        $menus = collect($household->wedding->menu_options ?? [])->pluck('label', 'key')
            ->put('children', __('rsvp.children_menu'));

        return new Content(markdown: 'mail.reply-confirmation', with: [
            'couple' => $household->wedding->couple_names,
            'deadline' => $household->wedding->rsvp_deadline?->copy()->settings(['locale' => $this->locale])->isoFormat('LL'),
            'link' => route('invitation.show', $household),
            'events' => $household->events->sortBy('starts_at')->map(fn (Event $event): array => [
                'name' => $event->name ?? __("invitation.event_types.{$event->type->value}"),
                'people' => $household->guests
                    ->map(function (Guest $guest) use ($event, $menus): ?string {
                        $response = $guest->responses->firstWhere('event_id', $event->id);

                        if ($response?->status !== ResponseStatus::Attending) {
                            return null;
                        }

                        $menu = $response->menu_choice ? $menus->get($response->menu_choice) : null;

                        return $guest->first_name.($menu ? " ({$menu})" : '');
                    })
                    ->filter()->values()->all(),
            ])->values()->all(),
            'allergies' => $household->guests->contains(fn (Guest $guest): bool => $guest->dietary_notes !== null),
        ]);
    }

    /**
     * @return list<Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn (): string => WeddingCalendar::for($this->household), 'hochzeit.ics')
                ->withMime('text/calendar'),
        ];
    }
}

<?php

namespace App\Mail;

use App\Models\GroupPhoto;
use App\Models\Household;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * «Gruppenfoto»: the witnesses need this household for a photo, now.
 */
class GroupPhotoCall extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Household $household, public GroupPhoto $groupPhoto) {}

    public function envelope(): Envelope
    {
        $couple = $this->household->wedding->couple_names;

        return new Envelope(
            from: new Address((string) config('mail.from.address'), "{$couple} via Hereby"),
            subject: __('evening.group_photo.subject', ['title' => $this->groupPhoto->title], $this->locale),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.group-photo-call', with: [
            'couple' => $this->household->wedding->couple_names,
            'title' => $this->groupPhoto->title,
            'place' => $this->groupPhoto->place,
        ]);
    }
}

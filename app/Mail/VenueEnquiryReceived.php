<?php

namespace App\Mail;

use App\Models\VenueEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A tour request from a Showcase venue's public page, to the venue.
 * Replying goes straight to the person who asked.
 */
class VenueEnquiryReceived extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public VenueEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->enquiry->email, $this->enquiry->name)],
            subject: "Besichtigung angefragt: {$this->enquiry->name}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.venue-enquiry-received');
    }
}

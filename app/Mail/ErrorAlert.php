<?php

namespace App\Mail;

use App\Models\LogEntry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;

/**
 * An error showed in production: what, where, how often, and the link to
 * the «Protokoll». Sent at once, not queued, so a broken queue still mails.
 */
class ErrorAlert extends Mailable
{
    public function __construct(public LogEntry $entry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Hereby-Fehler: '.Str::limit($this->entry->message, 70));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.error-alert', with: [
            'link' => route('admin.log.index'),
        ]);
    }
}

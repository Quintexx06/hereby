<?php

namespace App\Listeners;

use App\Actions\Log\DescribeLogRecord;
use App\Mail\ErrorAlert;
use App\Models\LogEntry;
use App\Models\User;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Keeps warnings and errors for the team's «Protokoll» and mails the team
 * when an error shows, at most once an hour per problem. Never throws: a
 * broken log must not break the request that logged.
 */
class RecordLogEntry
{
    private const LEVELS = ['warning', 'error', 'critical', 'alert', 'emergency'];

    private static bool $recording = false;

    public function __construct(private DescribeLogRecord $describe) {}

    public function handle(MessageLogged $event): void
    {
        if (self::$recording || ! in_array($event->level, self::LEVELS, true)) {
            return;
        }

        self::$recording = true;

        try {
            $this->record($event);
        } catch (Throwable) {
            // The database or mail is what failed; the normal log still has it.
        } finally {
            self::$recording = false;
        }
    }

    private function record(MessageLogged $event): void
    {
        $record = $this->describe->handle($event->level, $event->message, $event->context);
        $entry = LogEntry::query()->firstOrNew(['fingerprint' => $record['fingerprint']]);

        $entry->fill([
            ...$record,
            'level' => $event->level,
            'url' => app()->runningInConsole() ? 'Konsole' : request()->method().' '.$this->describe->path(request()->path()),
            'count' => $entry->exists ? $entry->count + 1 : 1,
            'first_seen_at' => $entry->first_seen_at ?? now(),
            'last_seen_at' => now(),
        ]);
        $entry->resolved_at = null;

        $mail = $entry->isError() && ($entry->mailed_at === null || $entry->mailed_at->lt(now()->subHour()));

        if ($mail) {
            $entry->mailed_at = now();
        }

        $entry->save();

        if ($mail) {
            $this->alert($entry);
        }
    }

    private function alert(LogEntry $entry): void
    {
        $recipients = User::query()->where('is_admin', true)->pluck('email')
            ->push(config('hereby.inbox'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($recipients !== []) {
            Mail::to($recipients)->send(new ErrorAlert($entry));
        }
    }
}

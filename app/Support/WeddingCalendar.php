<?php

namespace App\Support;

use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Household;
use Illuminate\Support\Collection;

/**
 * An iCalendar file (RFC 5545) for one household: the parts of the day it
 * attends, or all it is invited to before it answers. Times in UTC, so every
 * calendar app shows the venue's time correctly wherever the guest lives.
 */
class WeddingCalendar
{
    public static function for(Household $household): string
    {
        $household->loadMissing(['wedding', 'events', 'guests.responses']);
        $wedding = $household->wedding;
        $link = route('invitation.show', $household);

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Hereby//Wedding//DE',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
        ];

        foreach (self::events($household) as $event) {
            $name = $event->name ?? __("invitation.event_types.{$event->type->value}");
            $location = implode(', ', array_filter([$event->location_name, $event->address]));

            $lines = [...$lines,
                'BEGIN:VEVENT',
                'UID:hereby-'.$wedding->id.'-'.$event->id.'-'.$household->id.'@hereby.ch',
                'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
                'DTSTART:'.$event->starts_at->copy()->utc()->format('Ymd\THis\Z'),
                'DTEND:'.($event->ends_at ?? $event->starts_at->copy()->addHours(2))->copy()->utc()->format('Ymd\THis\Z'),
                'SUMMARY:'.self::escape($name.', '.$wedding->couple_names),
                ...($location !== '' ? ['LOCATION:'.self::escape($location)] : []),
                'DESCRIPTION:'.self::escape($link),
                'URL:'.$link,
                'END:VEVENT',
            ];
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", array_map(self::fold(...), $lines))."\r\n";
    }

    /**
     * @return Collection<int, Event>
     */
    private static function events(Household $household): Collection
    {
        $attending = $household->guests
            ->flatMap(fn ($guest) => $guest->responses)
            ->filter(fn (EventResponse $response): bool => $response->status === ResponseStatus::Attending)
            ->pluck('event_id')
            ->unique();

        return $household->responded_at === null
            ? $household->events->sortBy('starts_at')->values()
            : $household->events->whereIn('id', $attending)->sortBy('starts_at')->values();
    }

    private static function escape(string $text): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\;', '\\,', '\\n'], $text);
    }

    /**
     * Lines longer than 75 octets continue on the next line after a space.
     */
    private static function fold(string $line): string
    {
        return rtrim(chunk_split($line, 73, "\r\n "), "\r\n ");
    }
}

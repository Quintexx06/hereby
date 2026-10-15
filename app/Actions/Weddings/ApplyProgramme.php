<?php

namespace App\Actions\Weddings;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\Wedding;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Turns the programme the couple chose into the wedding's events.
 * Times are entered in Swiss local time and stored in UTC.
 */
class ApplyProgramme
{
    /**
     * Each row takes the next existing event of its type, so invitations and
     * answers stay attached; extra rows create events, and events no row
     * claims are removed. On a live wedding a new part is offered to every
     * household.
     *
     * @param  list<array{type: string, time: string, day_offset: int|string, name?: string|null}>  $rows
     */
    public function handle(Wedding $wedding, array $rows): void
    {
        DB::transaction(function () use ($wedding, $rows): void {
            $existing = $wedding->events()->orderBy('starts_at')->get()->groupBy(fn (Event $event): string => $event->type->value);
            $kept = [];

            foreach ($rows as $row) {
                $type = EventType::from($row['type']);
                $day = $wedding->wedding_date?->copy()->addDays((int) $row['day_offset']);
                $attributes = [
                    'type' => $type,
                    'name' => $row['name'] ?? null,
                    'starts_at' => Carbon::parse($day?->toDateString().' '.$row['time'], 'Europe/Zurich')->utc(),
                ];

                $event = $existing->get($type->value)?->shift();

                if ($event instanceof Event) {
                    // A part with an end keeps its length when it moves.
                    $event->update([...$attributes, 'ends_at' => $event->ends_at
                        ? $attributes['starts_at']->copy()->addSeconds((int) $event->starts_at->diffInSeconds($event->ends_at))
                        : null,
                    ]);
                } else {
                    $event = $wedding->events()->create([
                        ...$attributes,
                        // The civil ceremony is at the registry office, not the venue.
                        'location_name' => $type === EventType::CivilCeremony ? null : $wedding->venue_name,
                        'address' => $type === EventType::CivilCeremony ? null : $this->venueAddress($wedding),
                    ]);
                    $event->households()->attach($wedding->households()->pluck('id'));
                }

                $kept[] = $event->id;
            }

            $wedding->events()->whereNotIn('id', $kept)->delete();
        });
    }

    /**
     * Moves every event to a new wedding date by the same number of days,
     * keeping its Swiss local time.
     */
    public function moveDate(Wedding $wedding, CarbonInterface $from, CarbonInterface $to): void
    {
        $days = (int) $from->copy()->startOfDay()->diffInDays($to->copy()->startOfDay(), false);

        if ($days === 0) {
            return;
        }

        foreach ($wedding->events as $event) {
            $event->update([
                'starts_at' => $event->starts_at->copy()->timezone('Europe/Zurich')->addDays($days)->utc(),
                'ends_at' => $event->ends_at?->copy()->timezone('Europe/Zurich')->addDays($days)->utc(),
            ]);
        }
    }

    /**
     * Points the events at the wedding's current venue (after it changed).
     */
    public function syncVenue(Wedding $wedding): void
    {
        $wedding->events()
            ->where('type', '!=', EventType::CivilCeremony)
            ->update(['location_name' => $wedding->venue_name, 'address' => $this->venueAddress($wedding)]);
    }

    private function venueAddress(Wedding $wedding): ?string
    {
        if (! $wedding->venue_address) {
            return null;
        }

        return trim($wedding->venue_address.', '.$wedding->venue_postcode.' '.$wedding->venue_town, ', ');
    }
}

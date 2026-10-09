<?php

namespace App\Actions\Weddings;

use App\Enums\EventType;
use App\Models\Wedding;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Replaces a draft wedding's events with the programme the couple chose.
 * Times are entered in Swiss local time and stored in UTC.
 */
class ApplyProgramme
{
    /**
     * @param  list<array{type: string, time: string, day_offset: int|string, name?: string|null}>  $rows
     */
    public function handle(Wedding $wedding, array $rows): void
    {
        DB::transaction(function () use ($wedding, $rows): void {
            $wedding->events()->delete();

            foreach ($rows as $row) {
                $type = EventType::from($row['type']);
                $day = $wedding->wedding_date?->copy()->addDays((int) $row['day_offset']);

                $wedding->events()->create([
                    'type' => $type,
                    'name' => $row['name'] ?? null,
                    'starts_at' => Carbon::parse($day?->toDateString().' '.$row['time'], 'Europe/Zurich')->utc(),
                    // The civil ceremony is at the registry office, not the venue.
                    'location_name' => $type === EventType::CivilCeremony ? null : $wedding->venue_name,
                    'address' => $type === EventType::CivilCeremony ? null : $this->venueAddress($wedding),
                ]);
            }
        });
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

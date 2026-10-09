<?php

namespace App\Http\Resources;

use App\Enums\Locale;
use App\Models\Event;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A draft wedding as the setup pages edit it: raw values in form shape.
 *
 * @mixin Wedding
 */
class WeddingSetupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'partner_one' => $this->partner_one,
            'partner_two' => $this->partner_two,
            'couple_names' => $this->couple_names,
            'wedding_date' => $this->wedding_date?->toDateString(),
            'rsvp_deadline' => $this->rsvp_deadline?->toDateString(),
            'venue_name' => $this->venue_name,
            'venue_address' => $this->venue_address,
            'venue_postcode' => $this->venue_postcode,
            'venue_town' => $this->venue_town,
            'venue_lat' => $this->venue_lat === null ? null : (float) $this->venue_lat,
            'venue_lng' => $this->venue_lng === null ? null : (float) $this->venue_lng,
            'venue_reference' => $this->venue_reference,
            'celebration' => $this->celebration,
            'guest_estimate' => $this->guest_estimate,
            'languages' => $this->languages?->map(fn (Locale $locale): string => $locale->value)->values() ?? [],
            'default_locale' => $this->default_locale,
            'theme' => $this->theme,
            'events' => $this->events->map(fn (Event $event): array => [
                'type' => $event->type,
                'name' => $event->name,
                'time' => $event->starts_at->timezone('Europe/Zurich')->format('H:i'),
                'day_offset' => $this->wedding_date
                    ? (int) $this->wedding_date->diffInDays($event->starts_at->timezone('Europe/Zurich')->startOfDay(), false)
                    : 0,
            ])->values(),
        ];
    }
}

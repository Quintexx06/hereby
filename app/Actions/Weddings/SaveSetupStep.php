<?php

namespace App\Actions\Weddings;

use App\Enums\SetupStep;
use App\Models\Wedding;
use Illuminate\Support\Arr;

/**
 * Saves one step of the setup and moves the couple's progress forward.
 * Progress never moves back: revisiting step 2 keeps steps 3–7 unlocked.
 */
class SaveSetupStep
{
    public function __construct(private ApplyProgramme $applyProgramme) {}

    /**
     * @param  array<string, mixed>  $data  Validated input for this step.
     */
    public function handle(Wedding $wedding, SetupStep $step, array $data): void
    {
        match ($step) {
            SetupStep::Couple => $wedding->fill([
                ...Arr::only($data, ['partner_one', 'partner_two']),
                'couple_names' => $data['partner_one'].' & '.$data['partner_two'],
            ]),
            SetupStep::Date => $wedding->fill(Arr::only($data, ['wedding_date', 'rsvp_deadline'])),
            SetupStep::Venue => $wedding->fill($this->venue($data)),
            SetupStep::Programme => $wedding->fill(['celebration' => $data['celebration']]),
            SetupStep::Guests => $wedding->fill(Arr::only($data, ['guest_estimate', 'languages', 'default_locale'])),
            SetupStep::Look => $wedding->fill([
                'theme' => $data['theme'],
                'look_styles' => $data['look_styles'] ?? [],
                'look_wishes' => $data['look_wishes'] ?? null,
            ]),
            SetupStep::Review => null,
        };

        $furthest = $step->next() ?? $step;

        if (! $wedding->setup_step || $furthest->position() > $wedding->setup_step->position()) {
            $wedding->setup_step = $furthest;
        }

        $wedding->save();

        match ($step) {
            SetupStep::Programme => $this->applyProgramme->handle($wedding, $data['events']),
            SetupStep::Venue => $this->applyProgramme->syncVenue($wedding),
            default => null,
        };
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function venue(array $data): array
    {
        $fields = ['venue_name', 'venue_address', 'venue_postcode', 'venue_town', 'venue_lat', 'venue_lng', 'venue_reference'];

        if (! empty($data['venue_undecided'])) {
            return array_fill_keys($fields, null);
        }

        return array_merge(array_fill_keys($fields, null), Arr::only($data, $fields));
    }
}

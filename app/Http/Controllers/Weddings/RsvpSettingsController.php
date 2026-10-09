<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Invitations\SendReminders;
use App\Http\Controllers\Controller;
use App\Http\Requests\Weddings\UpdateRsvpSettingsRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * "Antwortformular": the couple chooses what guests are asked when they reply.
 */
class RsvpSettingsController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        return Inertia::render('rsvp/Settings', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'rsvp_deadline' => $wedding->rsvp_deadline?->toDateString(),
                'theme' => $wedding->theme,
            ],
            'settings' => [
                'menus' => $wedding->menu_options ?? [],
                'children_menu' => $wedding->children_menu,
                'offers_shuttle' => $wedding->offers_shuttle,
                'offers_stay' => $wedding->offers_stay,
                'asks_song' => $wedding->asks_song,
                'sends_reminders' => $wedding->sends_reminders,
            ],
            'reminderDates' => $this->reminderDates($wedding),
        ]);
    }

    public function update(UpdateRsvpSettingsRequest $request, Wedding $wedding): RedirectResponse
    {
        $settings = $request->settings();
        $known = array_column($wedding->menu_options ?? [], 'key');

        /* A renamed menu keeps its key, so answers already given stay attached. */
        $menus = array_map(fn (string $label, ?string $key): array => [
            'key' => $key !== null && in_array($key, $known, true) ? $key : 'm'.Str::lower(Str::random(6)),
            'label' => $label,
        ], $settings['labels'], $settings['keys']);

        $wedding->update([
            'menu_options' => $menus === [] ? null : $menus,
            'children_menu' => $settings['children_menu'],
            'offers_shuttle' => $settings['offers_shuttle'],
            'offers_stay' => $settings['offers_stay'],
            'asks_song' => $settings['asks_song'],
            'sends_reminders' => $settings['sends_reminders'],
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Antwortformular gespeichert.']);

        return back();
    }

    /**
     * The days reminders still go out, so the couple knows what guests will get.
     *
     * @return list<string>
     */
    private function reminderDates(Wedding $wedding): array
    {
        if (! $wedding->rsvp_deadline) {
            return [];
        }

        $today = now('Europe/Zurich')->toDateString();

        return array_values(array_filter(
            array_map(fn (int $days): string => $wedding->rsvp_deadline->copy()->subDays($days)->toDateString(), SendReminders::STAGES),
            fn (string $date): bool => $date >= $today,
        ));
    }
}

<?php

namespace App\Http\Controllers\Guests;

use App\Actions\Weddings\BuildWeddingOverview;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * "Gäste": every household with its people, reply status and personal link.
 * Search and filters run in the browser; a wedding has at most ~1,000 rows.
 */
class GuestsController extends Controller
{
    public function __invoke(Wedding $wedding, BuildWeddingOverview $overview): Response
    {
        Gate::authorize('view', $wedding);

        $households = $overview->households($wedding)->load(['guests:id,household_id,first_name,last_name,is_child', 'events:id']);

        return Inertia::render('guests/Index', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'default_locale' => $wedding->default_locale,
                'languages' => $wedding->languages?->values() ?? [],
            ],
            'households' => $households->map(fn (Household $household): array => [
                'id' => $household->id,
                'name' => $household->name,
                'email' => $household->email,
                'locale' => $household->locale,
                'plus_one_allowed' => $household->plus_one_allowed,
                'reply_status' => $household->replyStatus(),
                'link' => route('invitation.show', $household),
                'event_ids' => $household->events->modelKeys(),
                'guests' => $household->guests->map(fn (Guest $guest): array => [
                    'id' => $guest->id,
                    'name' => trim($guest->first_name.' '.$guest->last_name),
                    'first_name' => $guest->first_name,
                    'last_name' => $guest->last_name,
                    'is_child' => $guest->is_child,
                ]),
            ]),
            'events' => $wedding->events()->get(['id', 'type', 'name', 'starts_at'])->map(fn (Event $event): array => [
                'id' => $event->id,
                'type' => $event->type,
                'name' => $event->name,
                'starts_at' => $event->starts_at->toIso8601String(),
            ]),
        ]);
    }
}

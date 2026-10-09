<?php

namespace App\Http\Resources;

use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * What one household needs to answer: its people, its events, its previous
 * answer and the couple's questions. Allergies are write-only: only whether
 * something is on file, never the text (CLAUDE.md rule 9).
 *
 * @mixin Household
 */
class ReplyFormResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $wedding = $this->wedding;
        $plusOne = $this->guests->firstWhere('is_plus_one', true);

        return [
            'household' => ['name' => $this->name, 'plusOneAllowed' => $this->plus_one_allowed],
            'wedding' => new WeddingResource($wedding),
            'open' => $wedding->acceptsReplies(),
            'links' => [
                'invitation' => route('invitation.show', $this->resource),
                'reply' => route('invitation.reply', $this->resource),
            ],
            'answered' => $this->responded_at !== null,
            'guests' => $this->guests->where('is_plus_one', false)->values()->map(fn (Guest $guest): array => [
                'id' => $guest->id,
                'firstName' => $guest->first_name,
                'lastName' => $guest->last_name,
                'isChild' => $guest->is_child,
                'hasDietaryNotes' => $guest->dietary_notes !== null,
            ]),
            'plusOne' => $plusOne ? [
                'firstName' => $plusOne->first_name,
                'menu' => $plusOne->responses->firstWhere('menu_choice', '!=', null)?->menu_choice,
                'hasDietaryNotes' => $plusOne->dietary_notes !== null,
            ] : null,
            'events' => EventResource::collection($this->events),
            'answers' => $this->guests->where('is_plus_one', false)->flatMap(fn (Guest $guest) => $guest->responses
                ->map(fn (EventResponse $response): array => [
                    'guestId' => $guest->id,
                    'eventId' => $response->event_id,
                    'status' => $response->status->value,
                    'menu' => $response->menu_choice,
                ]))->values(),
            'questions' => [
                'menus' => $wedding->menu_options ?? [],
                'childrenMenu' => $wedding->children_menu,
                'shuttle' => $wedding->offers_shuttle,
                'stay' => $wedding->offers_stay,
                'song' => $wedding->asks_song,
            ],
            'email' => $this->email,
            'extras' => [
                'shuttleSeats' => $this->shuttle_seats,
                'needsStay' => $this->needs_stay,
                'songWish' => $this->song_wish,
            ],
        ];
    }
}

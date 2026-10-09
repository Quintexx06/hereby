<?php

namespace App\Http\Resources;

use App\Enums\ResponseStatus;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Everything one household may see. Never add other households' data here.
 *
 * @mixin Household
 */
class InvitationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'household' => [
                'name' => $this->name,
                'plusOneAllowed' => $this->plus_one_allowed,
            ],
            'wedding' => new WeddingResource($this->wedding),
            'guests' => GuestResource::collection($this->guests),
            'events' => EventResource::collection($this->events),
            'rsvpOpen' => $this->wedding->acceptsReplies(),
            'links' => [
                'invitation' => route('invitation.show', $this->resource),
                'reply' => route('invitation.reply', $this->resource),
            ],
            'reply' => [
                'answered' => $this->responded_at !== null,
                'attending' => $this->guests->filter(fn (Guest $guest): bool => $guest->responses
                    ->contains(fn (EventResponse $response): bool => $response->status === ResponseStatus::Attending))->count(),
                'invited' => $this->guests->count(),
            ],
        ];
    }
}

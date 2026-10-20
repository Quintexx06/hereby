<?php

namespace App\Http\Resources;

use App\Actions\Evening\BuildEvening;
use App\Actions\Invitations\VisibleBlocks;
use App\Enums\ResponseStatus;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Support\PersonalLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Everything one household may see. Never add other households' data here.
 *
 * @mixin Household
 */
class InvitationResource extends JsonResource
{
    /** @var array<string, string>|null Links that replace the personal ones (the venue demo). */
    private ?array $links = null;

    /**
     * @param  array<string, string>  $links
     */
    public function withLinks(array $links): static
    {
        $this->links = $links;

        return $this;
    }

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
            'blocks' => app(VisibleBlocks::class)->handle(
                $this->wedding,
                array_values(array_map(intval(...), $this->events->modelKeys())),
                $this->locale->value,
            ),
            'rsvpOpen' => $this->wedding->acceptsReplies(),
            // «Der Abend» (Atelier and up); other guests only by first name and the couple's note.
            'evening' => app(BuildEvening::class)->handle($this->resource),
            // Null in the couple's preview, where the sample household has no link.
            'links' => $this->links ?? ($this->exists ? [
                'invitation' => PersonalLink::to($this->resource),
                'reply' => PersonalLink::to($this->resource, 'invitation.reply'),
                'calendar' => PersonalLink::to($this->resource, 'invitation.calendar'),
            ] : null),
            'reply' => [
                'answered' => $this->responded_at !== null,
                'attending' => $this->guests->filter(fn (Guest $guest): bool => $guest->responses
                    ->contains(fn (EventResponse $response): bool => $response->status === ResponseStatus::Attending))->count(),
                'invited' => $this->guests->count(),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

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
        ];
    }
}

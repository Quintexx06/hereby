<?php

namespace App\Http\Resources;

use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Wedding
 */
class WeddingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coupleNames' => $this->couple_names,
            'date' => $this->wedding_date->toDateString(),
            'rsvpDeadline' => $this->rsvp_deadline?->toDateString(),
            'theme' => $this->theme,
        ];
    }
}

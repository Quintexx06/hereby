<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Event
 */
class EventResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'startsAt' => $this->starts_at->toIso8601String(),
            'endsAt' => $this->ends_at?->toIso8601String(),
            'locationName' => $this->location_name,
            'address' => $this->address,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Wedding;
use App\Models\WeddingPhoto;
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
            'template' => $this->template,
            // The couple's own photos, cover first (spec 2026-10-13-couple-photos).
            'photos' => $this->couplePhotos->map(fn (WeddingPhoto $photo): array => [
                'url' => route('wedding-photos.show', $photo->token),
                'width' => $photo->width,
                'height' => $photo->height,
                'focus' => $photo->focus(),
            ])->values(),
        ];
    }
}

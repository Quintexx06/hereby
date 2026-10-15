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
            // The footer: who guests ask, and Hereby's privacy policy (on the
            // main domain, since a couple's own domain serves only guest pages).
            'contact' => $this->contact_name || $this->contact_email || $this->contact_phone ? [
                'name' => $this->contact_name,
                'email' => $this->contact_email,
                'phone' => $this->contact_phone,
            ] : null,
            'privacyUrl' => rtrim((string) config('app.url'), '/').'/datenschutz',
            // The one venue credit line (rule 3), for a connected Showcase venue only.
            'venueCredit' => ($venue = $this->showcaseVenue()) ? [
                'name' => $venue->name,
                'url' => rtrim((string) config('app.url'), '/').'/orte/'.$venue->slug,
            ] : null,
        ];
    }
}

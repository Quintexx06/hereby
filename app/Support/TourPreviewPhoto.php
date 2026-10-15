<?php

namespace App\Support;

use App\Models\PartnerPhoto;
use App\Models\WeddingPhoto;

/**
 * A venue's photo standing in for the couple's on the tour preview (3.3).
 * Never saved; it only points the site at the venue's photo link.
 */
class TourPreviewPhoto extends WeddingPhoto
{
    public static function from(PartnerPhoto $photo): self
    {
        $preview = new self;
        $preview->forceFill([
            'token' => $photo->token,
            'width' => $photo->width,
            'height' => $photo->height,
            'focus_x' => 50,
            'focus_y' => 50,
        ]);

        return $preview;
    }

    public function url(): string
    {
        return route('partner-photos.show', $this->token);
    }
}

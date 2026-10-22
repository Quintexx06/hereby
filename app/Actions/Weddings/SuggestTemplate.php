<?php

namespace App\Actions\Weddings;

use App\Enums\Celebration;
use App\Enums\WeddingTemplate;
use App\Models\Wedding;

/**
 * A plain, explainable design suggestion from the setup answers. The couple
 * always chooses; this only decides which design is marked first.
 */
class SuggestTemplate
{
    /**
     * @return array{template: WeddingTemplate, reason: string}
     */
    public function handle(Wedding $wedding): array
    {
        $postcode = (int) $wedding->venue_postcode;
        $town = $wedding->venue_town;

        return match (true) {
            $postcode >= 6500 && $postcode <= 6999 => [
                'template' => WeddingTemplate::Lido,
                'reason' => "Passt zum Süden: {$town}",
            ],
            $this->isAlpine($postcode) => [
                'template' => WeddingTemplate::Arve,
                'reason' => "Passt zu Bergen und See: {$town}",
            ],
            $this->isCity($postcode) => [
                'template' => WeddingTemplate::Raster,
                'reason' => "Passt zur Stadt: {$town}",
            ],
            $wedding->celebration === Celebration::Day => [
                'template' => WeddingTemplate::Garten,
                'reason' => 'Passt zu einem Fest bei Tageslicht',
            ],
            default => [
                'template' => WeddingTemplate::Salon,
                'reason' => 'Unser Klassiker, zeitlos am Abend',
            ],
        };
    }

    /**
     * Central Switzerland, Bernese Oberland, Valais and Graubünden postcodes.
     */
    private function isAlpine(int $postcode): bool
    {
        return ($postcode >= 6000 && $postcode <= 6499)
            || ($postcode >= 3700 && $postcode <= 3999)
            || ($postcode >= 1900 && $postcode <= 1999)
            || ($postcode >= 7000 && $postcode <= 7999);
    }

    /**
     * Zürich, Basel, Bern, Geneva and Lausanne city postcodes.
     */
    private function isCity(int $postcode): bool
    {
        return ($postcode >= 8000 && $postcode <= 8099)
            || ($postcode >= 4000 && $postcode <= 4059)
            || ($postcode >= 3000 && $postcode <= 3030)
            || ($postcode >= 1200 && $postcode <= 1299)
            || ($postcode >= 1000 && $postcode <= 1018);
    }
}

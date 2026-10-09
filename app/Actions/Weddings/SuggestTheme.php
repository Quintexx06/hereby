<?php

namespace App\Actions\Weddings;

use App\Enums\Celebration;
use App\Enums\WeddingTheme;
use App\Models\Wedding;

/**
 * A plain, explainable theme suggestion from the setup answers. The couple
 * always chooses; this only decides which card is open first.
 */
class SuggestTheme
{
    /**
     * @return array{theme: WeddingTheme, reason: string}
     */
    public function handle(Wedding $wedding): array
    {
        $postcode = (int) $wedding->venue_postcode;
        $town = $wedding->venue_town;

        return match (true) {
            $postcode >= 6500 && $postcode <= 6999 => [
                'theme' => WeddingTheme::Riviera,
                'reason' => "Passt zum Süden: {$town}",
            ],
            $this->isAlpine($postcode) => [
                'theme' => WeddingTheme::Alpine,
                'reason' => "Passt zu Bergen und See: {$town}",
            ],
            $wedding->celebration === Celebration::Day => [
                'theme' => WeddingTheme::Rose,
                'reason' => 'Passt zu einem Fest bei Tageslicht',
            ],
            default => [
                'theme' => WeddingTheme::Ivory,
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
}

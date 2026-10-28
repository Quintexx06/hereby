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
        $venue = (string) $wedding->venue_name;

        return match (true) {
            // What the venue is says more than where it is: a barn is Garten
            // even in the city, a castle is Salon even in the mountains.
            $this->isCountryside($venue) => [
                'template' => WeddingTemplate::Garten,
                'reason' => "Passt zu Hof, Scheune und Garten: {$venue}",
            ],
            $this->isGrand($venue) => [
                'template' => WeddingTemplate::Salon,
                'reason' => "Passt zu Schloss und Salon: {$venue}",
            ],
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
     * Barns, farms, vineyards and gardens: «Stallstube Riethof», «Eventschür».
     */
    private function isCountryside(string $venue): bool
    {
        return (bool) preg_match('/scheune|sch[uü]+r\b|stall|bauernhof|\bhof\b|weingut|rebberg|landgut|garten/iu', $venue);
    }

    /**
     * Castles, grand hotels and guild halls: «Schloss Hünigen», «Zunfthaus».
     */
    private function isGrand(string $venue): bool
    {
        return (bool) preg_match('/schloss|grandhotel|grand hotel|zunfthaus|palais|palace/iu', $venue);
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

<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * A date in a guest's mail, as people write it in their language:
 * «1er mai 2027», «1 May 2027» (British, never May 1), «1. Mai 2027».
 */
class GuestDate
{
    public static function long(CarbonInterface $date, ?string $locale): string
    {
        return match ($locale) {
            'fr' => $date->copy()->locale('fr')->isoFormat('Do MMMM YYYY'),
            'en' => $date->copy()->locale('en_GB')->isoFormat('LL'),
            default => $date->copy()->locale($locale ?? 'de_CH')->isoFormat('LL'),
        };
    }
}

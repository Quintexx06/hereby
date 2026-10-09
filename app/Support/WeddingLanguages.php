<?php

namespace App\Support;

use App\Enums\Locale;
use App\Models\Wedding;

/**
 * The languages a wedding writes in, main language first.
 */
class WeddingLanguages
{
    /**
     * @return list<string>
     */
    public static function of(Wedding $wedding): array
    {
        $languages = $wedding->languages?->map(fn (Locale $locale): string => $locale->value)->all() ?? [];

        return array_values(array_unique([$wedding->default_locale->value, ...$languages]));
    }
}

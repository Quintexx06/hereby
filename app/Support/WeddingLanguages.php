<?php

namespace App\Support;

use App\Enums\Locale;
use App\Models\Wedding;
use Illuminate\Http\Request;

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

    /**
     * For pages without a household (shared links): the visitor's browser
     * language when the couple writes in it, otherwise the main one.
     */
    public static function preferredBy(Request $request, Wedding $wedding): string
    {
        $languages = self::of($wedding);
        $short = array_combine(array_map(fn (string $locale): string => substr($locale, 0, 2), $languages), $languages);

        return $short[(string) $request->getPreferredLanguage(array_keys($short))] ?? $wedding->default_locale->value;
    }
}

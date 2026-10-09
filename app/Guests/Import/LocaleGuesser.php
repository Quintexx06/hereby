<?php

namespace App\Guests\Import;

use App\Enums\Locale;
use Illuminate\Support\Str;

/**
 * "Deutsch", "fr", "Italiano", "English" → a Locale.
 */
class LocaleGuesser
{
    public static function guess(?string $value): ?Locale
    {
        $value = Str::lower(Str::ascii(trim((string) $value)));

        return match (true) {
            $value === '' => null,
            Str::startsWith($value, ['de', 'ger', 'sch']) => Locale::GermanSwiss,
            Str::startsWith($value, ['fr', 'fra']) => Locale::French,
            Str::startsWith($value, ['it']) => Locale::Italian,
            Str::startsWith($value, ['en', 'eng']) => Locale::English,
            default => null,
        };
    }
}

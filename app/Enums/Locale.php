<?php

namespace App\Enums;

/**
 * Languages a household can read its wedding site in.
 */
enum Locale: string
{
    case GermanSwiss = 'de_CH';
    case French = 'fr';
    case Italian = 'it';
    case English = 'en';

    /**
     * The BCP 47 tag for the `lang` attribute and Intl APIs.
     */
    public function tag(): string
    {
        return str_replace('_', '-', $this->value);
    }
}

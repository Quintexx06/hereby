<?php

namespace App\Support;

use Illuminate\Support\Facades\Lang;

/**
 * Translation groups shared with Vue through Inertia (see ADR 0006).
 * Only list groups the frontend needs; each page load ships them all.
 */
class FrontendTranslations
{
    /**
     * @var list<string>
     */
    public const array GROUPS = ['common', 'invitation', 'rsvp'];

    /**
     * @return array<string, mixed>
     */
    public static function forCurrentLocale(): array
    {
        return collect(self::GROUPS)
            ->mapWithKeys(fn (string $group): array => [$group => Lang::get($group)])
            ->all();
    }
}

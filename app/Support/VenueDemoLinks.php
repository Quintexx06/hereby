<?php

namespace App\Support;

use App\Enums\WeddingTemplate;
use App\Models\Partner;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\URL;

/**
 * The signed links inside a venue's demo: the site, the reply form and the
 * calendar. They all end when the link the team sent ends, so opening the
 * demo never stretches its 90 days.
 */
class VenueDemoLinks
{
    public const DAYS_VALID = 90;

    public static function fresh(Partner $venue): string
    {
        return URL::temporarySignedRoute('venues.demo', now()->addDays(self::DAYS_VALID), ['partner' => $venue->slug]);
    }

    /**
     * @return array{invitation: string, reply: string, update: string, calendar: string}
     */
    public static function for(Partner $venue, WeddingTemplate $template, int $expires): array
    {
        $until = CarbonImmutable::createFromTimestamp($expires);
        $sign = fn (string $route, array $extra = []): string => URL::temporarySignedRoute($route, $until, ['partner' => $venue->slug, ...$extra]);

        return [
            'invitation' => $sign('venues.demo').'&vorlage='.$template->value,
            'reply' => $sign('venues.demo.reply', ['vorlage' => $template->value]),
            'update' => $sign('venues.demo.reply.update', ['vorlage' => $template->value]),
            'calendar' => $sign('venues.demo.calendar'),
        ];
    }
}

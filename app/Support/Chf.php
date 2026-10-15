<?php

namespace App\Support;

/**
 * Prices as the Swiss write them: «CHF 290», «CHF 1’490».
 */
class Chf
{
    public static function format(int $rappen): string
    {
        return 'CHF '.number_format($rappen / 100, 0, '.', '’');
    }

    /**
     * A configured price (config/hereby.php), e.g. `plans.signature`.
     */
    public static function price(string $key): string
    {
        return self::format(config()->integer("hereby.{$key}"));
    }
}

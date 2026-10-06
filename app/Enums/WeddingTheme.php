<?php

namespace App\Enums;

/**
 * Curated, designer-set themes for guest-facing wedding sites. Each case has
 * a matching `[data-theme]` block in resources/css/themes/.
 */
enum WeddingTheme: string
{
    case Ivory = 'ivory';
    case Alpine = 'alpine';
    case Riviera = 'riviera';
}

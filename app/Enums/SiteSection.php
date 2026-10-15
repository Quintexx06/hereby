<?php

namespace App\Enums;

/**
 * Built-in parts of a guest site a couple may leave out. Everything a
 * guest needs to answer always stays (rule 1).
 */
enum SiteSection: string
{
    case Opening = 'opening';
    case Calendar = 'calendar';
    case Ornaments = 'ornaments';
    case Closing = 'closing';
}

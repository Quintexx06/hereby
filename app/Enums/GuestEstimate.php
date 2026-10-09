<?php

namespace App\Enums;

/**
 * Rough wedding size given during setup. The guest list makes it exact later.
 */
enum GuestEstimate: string
{
    case UpTo50 = 'up_to_50';
    case UpTo100 = 'up_to_100';
    case UpTo150 = 'up_to_150';
    case Over150 = 'over_150';
}

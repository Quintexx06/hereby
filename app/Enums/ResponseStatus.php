<?php

namespace App\Enums;

/**
 * A guest's answer for one event.
 */
enum ResponseStatus: string
{
    case Pending = 'pending';
    case Attending = 'attending';
    case Declined = 'declined';
}

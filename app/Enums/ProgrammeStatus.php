<?php

namespace App\Enums;

/**
 * Where a guest's offer stands with the witnesses.
 */
enum ProgrammeStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
}

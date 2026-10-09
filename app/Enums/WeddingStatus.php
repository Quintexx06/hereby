<?php

namespace App\Enums;

/**
 * A wedding is a draft until the couple finishes the setup.
 */
enum WeddingStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
}

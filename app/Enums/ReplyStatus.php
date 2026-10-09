<?php

namespace App\Enums;

/**
 * Where a household stands with its invitation (roadmap 1.11). "Opened but
 * not answered" gets a different nudge from "never opened".
 */
enum ReplyStatus: string
{
    case NeverOpened = 'never_opened';
    case Opened = 'opened';
    case Answered = 'answered';

    public static function for(bool $opened, bool $answered): self
    {
        return match (true) {
            $answered => self::Answered,
            $opened => self::Opened,
            default => self::NeverOpened,
        };
    }
}

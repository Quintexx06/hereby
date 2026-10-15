<?php

namespace App\Enums;

/**
 * Who a partner account belongs to (spec 2026-10-15-partner-accounts).
 */
enum PartnerType: string
{
    case Venue = 'venue';
    case Photographer = 'photographer';
    case Planner = 'planner';

    public function label(): string
    {
        return match ($this) {
            self::Venue => 'Location',
            self::Photographer => 'Fotograf:in',
            self::Planner => 'Planer:in',
        };
    }

    /**
     * Venues and planners get the final numbers; photographers do not.
     */
    public function seesFinalNumbers(): bool
    {
        return $this !== self::Photographer;
    }
}

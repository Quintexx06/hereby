<?php

namespace App\Enums;

/**
 * The seven steps of the couple's setup, in order. The value is the URL slug.
 */
enum SetupStep: string
{
    case Couple = 'paar';
    case Date = 'datum';
    case Venue = 'ort';
    case Programme = 'ablauf';
    case Guests = 'gaeste';
    case Look = 'look';
    case Review = 'uebersicht';

    public function position(): int
    {
        return array_search($this, self::cases(), true) + 1;
    }

    public function next(): ?self
    {
        return self::cases()[$this->position()] ?? null;
    }

    public function previous(): ?self
    {
        return self::cases()[$this->position() - 2] ?? null;
    }

    /**
     * Whether a couple whose furthest step is `$furthest` may open this one.
     */
    public function isReachableFrom(?self $furthest): bool
    {
        return $this->position() <= ($furthest ?? self::Couple)->position();
    }
}

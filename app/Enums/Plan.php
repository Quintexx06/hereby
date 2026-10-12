<?php

namespace App\Enums;

/**
 * What a couple has paid for (docs/specs/2026-10-12-paywall-and-memories-design.md).
 * Building is free; a plan sends the personal links. Atelier adds the
 * memories (guest photos, live wall, voice wishes). Couture is sold in
 * person, so it has no online checkout.
 */
enum Plan: string
{
    case Signature = 'signature';
    case Atelier = 'atelier';
    case Couture = 'couture';

    /**
     * Price in Rappen (config/hereby.php).
     */
    public function price(): int
    {
        return config()->integer("hereby.plans.{$this->value}");
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function rank(): int
    {
        return match ($this) {
            self::Signature => 1,
            self::Atelier => 2,
            self::Couture => 3,
        };
    }

    public function includesMemories(): bool
    {
        return $this->rank() >= self::Atelier->rank();
    }

    public function isSoldOnline(): bool
    {
        return $this !== self::Couture;
    }
}

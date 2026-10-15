<?php

namespace App\Enums;

/**
 * Extras bought once on top of a plan (spec 2026-10-13). Couture includes
 * all but «Erinnerungen behalten», which is bought per year. «Massanfertigung»
 * (spec 2026-10-15-bespoke-and-design-freedom) has its own page.
 */
enum Addon: string
{
    case Newspaper = 'newspaper';
    case ChaseService = 'chase_service';
    case CustomDomain = 'custom_domain';
    case KeepMemories = 'keep_memories';
    case Bespoke = 'bespoke';

    /**
     * Price in Rappen (config/hereby.php).
     */
    public function price(): int
    {
        return config()->integer("hereby.addons.{$this->value}");
    }

    public function label(): string
    {
        return match ($this) {
            self::Newspaper => 'Hochzeitszeitung',
            self::ChaseService => 'Wir fassen nach',
            self::CustomDomain => 'Eigene Domain',
            self::KeepMemories => 'Erinnerungen behalten',
            self::Bespoke => 'Massanfertigung',
        };
    }

    public function includedIn(?Plan $plan): bool
    {
        return $plan === Plan::Couture && $this !== self::KeepMemories;
    }

    /**
     * The plan a wedding needs before it can buy this: memories need Atelier.
     */
    public function requires(): Plan
    {
        return $this === self::KeepMemories ? Plan::Atelier : Plan::Signature;
    }

    /**
     * Bought again each year rather than once.
     */
    public function isRepeatable(): bool
    {
        return $this === self::KeepMemories;
    }
}

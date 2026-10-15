<?php

namespace App\Enums;

/**
 * Where a «Massanfertigung» stands, as the couple and the team see it.
 */
enum BriefStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case InProgress = 'in_progress';
    case Ready = 'ready';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Entwurf',
            self::Submitted => 'Eingegangen',
            self::InProgress => 'In Arbeit',
            self::Ready => 'Fertig',
        };
    }
}

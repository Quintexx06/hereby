<?php

namespace App\Enums;

/**
 * What a guest offers for the evening (spec 2026-10-13).
 */
enum ProgrammeKind: string
{
    case Speech = 'speech';
    case Game = 'game';
    case Music = 'music';
    case Video = 'video';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Speech => 'Rede',
            self::Game => 'Spiel',
            self::Music => 'Musik',
            self::Video => 'Film oder Diashow',
            self::Other => 'Etwas anderes',
        };
    }
}

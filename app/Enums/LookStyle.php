<?php

namespace App\Enums;

/**
 * The style directions a couple picks for their site (up to three), on top of
 * the colour theme. They brief the team's hand-tuning; they don't switch
 * templates.
 */
enum LookStyle: string
{
    case Classic = 'classic';
    case Modern = 'modern';
    case Romantic = 'romantic';
    case Natural = 'natural';
    case Boho = 'boho';
    case Rustic = 'rustic';
    case Elegant = 'elegant';
    case Mediterranean = 'mediterranean';
    case Vintage = 'vintage';
    case Minimal = 'minimal';
    case Playful = 'playful';
    case Mountain = 'mountain';

    /** How many directions a couple may pick. */
    public const MAX = 3;
}

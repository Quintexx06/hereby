<?php

namespace App\Enums;

/**
 * The layout and motion of a guest site, independent of its colour theme:
 * Atelier (editorial), Garten (botanical, arched), Panorama (cinematic,
 * horizontal). Each has a template component in pages/invitation.
 */
enum WeddingTemplate: string
{
    case Atelier = 'atelier';
    case Garten = 'garten';
    case Panorama = 'panorama';
}

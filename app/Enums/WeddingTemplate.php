<?php

namespace App\Enums;

/**
 * The layout and motion of a guest site, independent of its colour theme:
 * Atelier (editorial), Garten (botanical, arched), Panorama (cinematic,
 * horizontal). Each has a template component in pages/invitation and its
 * own display serif.
 */
enum WeddingTemplate: string
{
    case Atelier = 'atelier';
    case Garten = 'garten';
    case Panorama = 'panorama';

    /**
     * The roman file of the template's display serif (ADR 0014), preloaded
     * on its guest pages because the couple's names are set in it.
     */
    public function serifFile(): string
    {
        return match ($this) {
            self::Atelier => 'node_modules/@fontsource-variable/bodoni-moda/files/bodoni-moda-latin-standard-normal.woff2',
            self::Garten => 'node_modules/@fontsource-variable/cormorant-garamond/files/cormorant-garamond-latin-wght-normal.woff2',
            self::Panorama => 'node_modules/@fontsource-variable/fraunces/files/fraunces-latin-standard-normal.woff2',
        };
    }
}

<?php

namespace App\Enums;

/**
 * The five designs a couple chooses from (ADR 0015). Each is finished by
 * us: its layout, type and colours are fixed, only the couple's content
 * changes. Anything else is made to measure (Massanfertigung).
 */
enum WeddingTemplate: string
{
    /** An engraved card: black tie, grand hotels, guildhalls, castles. */
    case Salon = 'salon';

    /** The Swiss grid: modern venues, museums, a civil ceremony and dinner. */
    case Raster = 'raster';

    /** A private-press book: gardens, vineyards, barns, estates. */
    case Garten = 'garten';

    /** Stone pine and wool: mountain hotels, huts, alpine lakes. */
    case Arve = 'arve';

    /** A summer lido: lakes, Ticino, the south, daytime and colour. */
    case Lido = 'lido';

    /**
     * Also reads the keys of the three templates before ADR 0015, so links
     * already sent (venue demos, samples) keep opening a design.
     */
    public static function fromKey(self|string|null $key): ?self
    {
        if ($key instanceof self) {
            return $key;
        }

        return self::tryFrom((string) $key) ?? match ($key) {
            'atelier' => self::Salon,
            'panorama' => self::Arve,
            default => null,
        };
    }

    /**
     * The design's own stylesheet, a Vite entry: a guest page carries only
     * its design's layout on top of the shared guest styles.
     */
    public function stylesheet(): string
    {
        return "resources/css/designs/{$this->value}.css";
    }

    /**
     * The roman files of the design's faces (resources/css/base/wedding-fonts.css),
     * display first, preloaded on its guest pages: the names are set in them.
     *
     * @return list<string>
     */
    public function fonts(): array
    {
        $file = fn (string $path): string => "node_modules/@fontsource{$path}.woff2";

        return match ($this) {
            self::Salon => [$file('/ballet/files/ballet-latin-400-normal'), $file('/tenor-sans/files/tenor-sans-latin-400-normal')],
            self::Raster => [$file('-variable/schibsted-grotesk/files/schibsted-grotesk-latin-wght-normal')],
            self::Garten => [$file('-variable/cormorant-garamond/files/cormorant-garamond-latin-wght-normal'), $file('-variable/archivo/files/archivo-latin-wght-normal')],
            self::Arve => [$file('-variable/sofia-sans-condensed/files/sofia-sans-condensed-latin-wght-normal')],
            self::Lido => [$file('/shrikhand/files/shrikhand-latin-400-normal'), $file('-variable/figtree/files/figtree-latin-wght-normal')],
        };
    }
}

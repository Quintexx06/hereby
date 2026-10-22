<?php

namespace App\Support;

use App\Enums\WeddingTemplate;
use App\Models\WeddingPhoto;

/**
 * One of Hereby's own landing photos standing in for the couple's on a
 * venue's sales demo, while the venue has no photos of its own yet.
 * Never saved; the files are self-hosted (public/images/landing).
 */
class StockPreviewPhoto extends WeddingPhoto
{
    /** @var array<string, array{int, int}> file => [width, height] */
    public const PHOTOS = [
        'veil-kiss.webp' => [1000, 1250],
        'lake-jetty-1200.webp' => [1200, 900],
        'table-candles.webp' => [1000, 1250],
    ];

    private string $file = '';

    /**
     * The stock photos in the order a design shows them: Arve opens on its
     * landscape, every other design on the couple.
     *
     * @return list<string>
     */
    public static function filesFor(WeddingTemplate $template): array
    {
        $files = array_keys(self::PHOTOS);

        return $template === WeddingTemplate::Arve
            ? ['lake-jetty-1200.webp', ...array_values(array_diff($files, ['lake-jetty-1200.webp']))]
            : $files;
    }

    public static function make(string $file): self
    {
        $photo = new self;
        $photo->file = $file;
        $photo->forceFill([
            'width' => self::PHOTOS[$file][0],
            'height' => self::PHOTOS[$file][1],
            'focus_x' => 50,
            'focus_y' => 50,
        ]);

        return $photo;
    }

    public function url(): string
    {
        return asset('images/landing/'.$this->file);
    }
}

<?php

namespace App\Support;

use GdImage;

/**
 * Turns a photo from a phone or camera into a web copy: upright from the
 * camera's orientation note, the longer edge at most `$edge`, as JPEG.
 * Very large images are refused rather than risk the server's memory.
 */
class WebImage
{
    private const int MAX_PIXELS = 60_000_000;

    /**
     * @return array{bytes: string, width: int, height: int}|null
     */
    public static function jpeg(string $bytes, int $edge, int $quality = 82): ?array
    {
        $size = getimagesizefromstring($bytes);

        if ($size === false || $size[0] * $size[1] > self::MAX_PIXELS) {
            return null;
        }

        $image = imagecreatefromstring($bytes);

        if ($image === false) {
            return null;
        }

        $image = self::upright($image, $bytes);
        $image = imagescale($image, ...self::fit(imagesx($image), imagesy($image), $edge)) ?: $image;

        ob_start();
        imagejpeg($image, null, $quality);

        return ['bytes' => (string) ob_get_clean(), 'width' => imagesx($image), 'height' => imagesy($image)];
    }

    /**
     * Phones store portrait photos sideways with a note on how to turn them.
     */
    private static function upright(GdImage $image, string $bytes): GdImage
    {
        $exif = function_exists('exif_read_data') ? @exif_read_data('data://image/jpeg;base64,'.base64_encode($bytes)) : false;
        $degrees = match (is_array($exif) ? ($exif['Orientation'] ?? 1) : 1) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        return $degrees === 0 ? $image : (imagerotate($image, $degrees, 0) ?: $image);
    }

    /**
     * @return array{int, int} Width and height with the longer edge at most $edge.
     */
    private static function fit(int $width, int $height, int $edge): array
    {
        $scale = min(1, $edge / max($width, $height));

        return [max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale))];
    }
}

<?php

namespace App\Support;

/**
 * Turns a photo from a phone or camera into a web copy: upright from the
 * camera's orientation note, the longer edge at most `$edge`, as JPEG.
 * A photo is only opened when it fits in the memory PHP has left, so a huge
 * one is refused instead of taking the request or the queue worker down.
 * It is scaled before it is turned, so at most one full-size copy is held.
 */
class WebImage
{
    /** A 48 MP phone photo passes; 100 MP sensors and stitched panoramas do not. */
    private const int MAX_PIXELS = 50_000_000;

    /** GD holds a true-colour image as four bytes per pixel. */
    private const int BYTES_PER_PIXEL = 4;

    /** Room for the decoder, the scaled copy and the JPEG it writes. */
    private const int HEADROOM_BYTES = 32 * 1024 * 1024;

    /**
     * @return array{bytes: string, width: int, height: int}|null
     */
    public static function jpeg(string $bytes, int $edge, int $quality = 82): ?array
    {
        $image = self::open($bytes, $edge);

        if ($image === null) {
            return null;
        }

        ob_start();
        imagejpeg($image, null, $quality);

        return ['bytes' => (string) ob_get_clean(), 'width' => imagesx($image), 'height' => imagesy($image)];
    }

    /**
     * A lighter copy for phones: WebP is about a third smaller than JPEG at
     * the same look. Null where GD was built without WebP.
     *
     * @return array{bytes: string, width: int, height: int}|null
     */
    public static function webp(string $bytes, int $edge, int $quality = 74): ?array
    {
        $image = function_exists('imagewebp') ? self::open($bytes, $edge) : null;

        if ($image === null) {
            return null;
        }

        ob_start();
        imagewebp($image, null, $quality);

        return ['bytes' => (string) ob_get_clean(), 'width' => imagesx($image), 'height' => imagesy($image)];
    }

    /**
     * The photo upright, its longer edge at most $edge; null when it cannot be read.
     */
    private static function open(string $bytes, int $edge): ?\GdImage
    {
        $size = getimagesizefromstring($bytes);

        if ($size === false || ! self::fitsInMemory($size[0], $size[1])) {
            return null;
        }

        $degrees = self::turn($bytes);
        $image = imagecreatefromstring($bytes);

        if ($image === false) {
            return null;
        }

        $image = imagescale($image, ...self::fit(imagesx($image), imagesy($image), $edge)) ?: $image;

        return $degrees === 0 ? $image : (imagerotate($image, $degrees, 0) ?: $image);
    }

    /**
     * Whether GD can open an image this size within PHP's `memory_limit`.
     */
    private static function fitsInMemory(int $width, int $height): bool
    {
        $pixels = $width * $height;

        if ($pixels > self::MAX_PIXELS) {
            return false;
        }

        $limit = ini_parse_quantity((string) ini_get('memory_limit'));

        return $limit <= 0 || $pixels * self::BYTES_PER_PIXEL + self::HEADROOM_BYTES <= $limit - memory_get_usage(true);
    }

    /**
     * Phones store portrait photos sideways with a note on how to turn them.
     */
    private static function turn(string $bytes): int
    {
        $exif = function_exists('exif_read_data') ? @exif_read_data('data://image/jpeg;base64,'.base64_encode($bytes)) : false;

        return match (is_array($exif) ? ($exif['Orientation'] ?? 1) : 1) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };
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

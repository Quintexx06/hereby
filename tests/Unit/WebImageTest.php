<?php

namespace Tests\Unit;

use App\Support\WebImage;
use PHPUnit\Framework\TestCase;

class WebImageTest extends TestCase
{
    private function photo(int $width, int $height): string
    {
        ob_start();
        imagejpeg(imagecreatetruecolor($width, $height));

        return (string) ob_get_clean();
    }

    /**
     * The orientation note a phone writes: "turn 90° clockwise to view".
     */
    private function takenSideways(string $jpeg): string
    {
        $tiff = "MM\x00\x2A".pack('N', 8).pack('n', 1).pack('nnN', 0x0112, 3, 1).pack('nn', 6, 0).pack('N', 0);
        $exif = "Exif\x00\x00".$tiff;

        return substr($jpeg, 0, 2)."\xFF\xE1".pack('n', 2 + strlen($exif)).$exif.substr($jpeg, 2);
    }

    public function test_a_sideways_phone_photo_comes_out_upright_within_the_edge(): void
    {
        $copy = WebImage::jpeg($this->takenSideways($this->photo(3000, 2000)), 1600);

        $this->assertNotNull($copy);
        $this->assertSame([1067, 1600], [$copy['width'], $copy['height']]);
        $this->assertSame([1067, 1600], array_slice((array) getimagesizefromstring($copy['bytes']), 0, 2));
    }

    public function test_a_photo_too_large_for_the_memory_left_is_refused_instead_of_opened(): void
    {
        $photo = $this->photo(3000, 2000);
        $limit = (string) ini_get('memory_limit');
        ini_set('memory_limit', (string) (memory_get_usage(true) + 40 * 1024 * 1024));

        try {
            $this->assertNull(WebImage::jpeg($photo, 1600));
        } finally {
            ini_set('memory_limit', $limit);
        }

        $this->assertNotNull(WebImage::jpeg($photo, 1600));
    }
}

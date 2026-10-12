<?php

namespace App\Jobs;

use App\Models\GuestPhoto;
use GdImage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

/**
 * A small JPEG of a guest photo for the couple's gallery, so a page of
 * photos does not load every original. Turned upright from the camera's
 * orientation. Very large images are left alone and shown as they are.
 */
class MakePhotoThumbnail implements ShouldQueue
{
    use Queueable;

    public const int EDGE = 800;

    private const int MAX_PIXELS = 60_000_000;

    public function __construct(public GuestPhoto $photo) {}

    public function handle(): void
    {
        $disk = Storage::disk(GuestPhoto::DISK);
        $bytes = $disk->get($this->photo->path);
        $size = $bytes === null ? false : getimagesizefromstring($bytes);

        if ($bytes === null || $size === false || $size[0] * $size[1] > self::MAX_PIXELS) {
            return;
        }

        $image = imagecreatefromstring($bytes);

        if ($image === false) {
            return;
        }

        $image = $this->upright($image, $bytes);
        $image = imagescale($image, ...$this->fit(imagesx($image), imagesy($image))) ?: $image;

        ob_start();
        imagejpeg($image, null, 80);
        $path = GuestPhoto::directoryFor($this->photo->wedding_id).'/thumbs/'.pathinfo($this->photo->path, PATHINFO_FILENAME).'.jpg';
        $disk->put($path, (string) ob_get_clean());

        $this->photo->forceFill(['thumbnail_path' => $path])->save();
    }

    /**
     * Phones store portrait photos sideways with a note on how to turn them.
     */
    private function upright(GdImage $image, string $bytes): GdImage
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
     * @return array{int, int} Width and height with the longer edge at EDGE.
     */
    private function fit(int $width, int $height): array
    {
        $scale = min(1, self::EDGE / max($width, $height));

        return [max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale))];
    }
}

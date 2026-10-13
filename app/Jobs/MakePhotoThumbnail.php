<?php

namespace App\Jobs;

use App\Models\GuestPhoto;
use App\Support\WebImage;
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

    public function __construct(public GuestPhoto $photo) {}

    public function handle(): void
    {
        $disk = Storage::disk(GuestPhoto::DISK);
        $bytes = $disk->get($this->photo->path);
        $copy = $bytes === null ? null : WebImage::jpeg($bytes, self::EDGE, 80);

        if ($copy === null) {
            return;
        }

        $path = GuestPhoto::directoryFor($this->photo->wedding_id).'/thumbs/'.pathinfo($this->photo->path, PATHINFO_FILENAME).'.jpg';
        $disk->put($path, $copy['bytes']);

        $this->photo->forceFill(['thumbnail_path' => $path])->save();
    }
}

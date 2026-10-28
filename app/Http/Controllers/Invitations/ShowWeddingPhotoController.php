<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\WeddingPhoto;
use App\Support\WebImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * A couple photo for their site. The token is the credential, as on the
 * personal links (ADR 0005), so it works in the couple's preview, on every
 * personal link and on the couple's own domain. `?w=800` or `?w=1200` asks
 * for a lighter WebP copy, made from the web copy the first time and kept.
 */
class ShowWeddingPhotoController extends Controller
{
    private const array HEADERS = ['Cache-Control' => 'public, max-age=86400'];

    public function __invoke(Request $request, WeddingPhoto $weddingPhoto): StreamedResponse
    {
        $disk = Storage::disk(WeddingPhoto::DISK);
        $width = $request->integer('w');

        if (in_array($width, WeddingPhoto::WIDTHS, true) && $width < $weddingPhoto->width) {
            $path = $weddingPhoto->variantPath($width);

            if ($disk->exists($path) || $this->makeVariant($weddingPhoto, $width, $path)) {
                return $disk->response($path, null, ['Content-Type' => 'image/webp', ...self::HEADERS]);
            }
        }

        return $disk->response($weddingPhoto->path, null, ['Content-Type' => 'image/jpeg', ...self::HEADERS]);
    }

    /**
     * The copy `$width` pixels wide; false when this server cannot write WebP.
     */
    private function makeVariant(WeddingPhoto $photo, int $width, string $path): bool
    {
        $edge = (int) round($width * max($photo->width, $photo->height) / $photo->width);
        $copy = WebImage::webp((string) Storage::disk(WeddingPhoto::DISK)->get($photo->path), $edge);

        return $copy !== null && Storage::disk(WeddingPhoto::DISK)->put($path, $copy['bytes']);
    }
}

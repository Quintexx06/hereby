<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\WeddingPhoto;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * A couple photo for their site. The token is the credential, as on the
 * personal links (ADR 0005), so it works in the couple's preview, on every
 * personal link and on the couple's own domain.
 */
class ShowWeddingPhotoController extends Controller
{
    public function __invoke(WeddingPhoto $weddingPhoto): StreamedResponse
    {
        return Storage::disk(WeddingPhoto::DISK)->response($weddingPhoto->path, null, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}

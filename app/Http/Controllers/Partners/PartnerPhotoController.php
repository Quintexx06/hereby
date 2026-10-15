<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Content\CouplePhotoController;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerPhoto;
use App\Support\WebImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * A venue's own photos on «Profil»: up to six web copies for its public
 * page and the tour preview, the first one on top.
 */
class PartnerPhotoController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = $request->user()?->partner;
        abort_unless($partner->isVenue(), 403);

        $request->validate([
            'photo' => ['required', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(config()->integer('hereby.memories.max_kilobytes'))],
        ]);

        if ($partner->photos()->count() >= PartnerPhoto::MAX) {
            throw ValidationException::withMessages(['photo' => 'Sechs Fotos sind das Maximum. Löscht zuerst eines.']);
        }

        $copy = WebImage::jpeg((string) $request->file('photo')?->get(), CouplePhotoController::EDGE);

        if ($copy === null) {
            throw ValidationException::withMessages(['photo' => 'Dieses Foto können wir nicht lesen. Versucht ein anderes.']);
        }

        $path = PartnerPhoto::directoryFor($partner->id).'/'.Str::uuid().'.jpg';
        Storage::disk(PartnerPhoto::DISK)->put($path, $copy['bytes']);

        $photo = new PartnerPhoto;
        $photo->partner_id = $partner->id;
        $photo->path = $path;
        $photo->width = $copy['width'];
        $photo->height = $copy['height'];
        $photo->position = (int) $partner->photos()->max('position') + 1;
        $photo->save();

        return back();
    }

    public function destroy(Request $request, PartnerPhoto $partnerPhoto): RedirectResponse
    {
        abort_unless($partnerPhoto->partner_id === $request->user()?->partner_id, 403);

        $partnerPhoto->delete();

        return back();
    }

    /**
     * Public by design: a venue's photos are for its page and the preview.
     */
    public function show(PartnerPhoto $partnerPhoto): StreamedResponse
    {
        return Storage::disk(PartnerPhoto::DISK)->response($partnerPhoto->path, null, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}

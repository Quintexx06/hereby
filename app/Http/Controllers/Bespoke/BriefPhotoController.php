<?php

namespace App\Http\Controllers\Bespoke;

use App\Http\Controllers\Content\CouplePhotoController;
use App\Http\Controllers\Controller;
use App\Models\BriefPhoto;
use App\Models\DesignBrief;
use App\Models\Wedding;
use App\Support\WebImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Inspiration photos for the brief: up to twelve web copies, seen only by
 * the couple and the team (both pass the wedding's `update` policy).
 */
class BriefPhotoController extends Controller
{
    public function store(Request $request, Wedding $wedding): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(config()->integer('hereby.memories.max_kilobytes'))],
        ]);

        $brief = $wedding->designBrief()->firstOrCreate();

        if ($brief->photos()->count() >= DesignBrief::MAX_PHOTOS) {
            throw ValidationException::withMessages(['photo' => 'Zwölf Bilder sind das Maximum. Löscht zuerst eines.']);
        }

        $copy = WebImage::jpeg((string) $request->file('photo')?->get(), CouplePhotoController::EDGE);

        if ($copy === null) {
            throw ValidationException::withMessages(['photo' => 'Dieses Bild können wir nicht lesen. Versucht ein anderes.']);
        }

        $path = BriefPhoto::directoryFor($wedding->id).'/'.Str::uuid().'.jpg';
        Storage::disk(BriefPhoto::DISK)->put($path, $copy['bytes']);

        $photo = new BriefPhoto;
        $photo->design_brief_id = $brief->id;
        $photo->path = $path;
        $photo->save();

        return back();
    }

    public function show(Wedding $wedding, BriefPhoto $briefPhoto): StreamedResponse
    {
        abort_unless($briefPhoto->brief->wedding_id === $wedding->id, 404);

        return Storage::disk(BriefPhoto::DISK)->response($briefPhoto->path, null, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function destroy(Wedding $wedding, BriefPhoto $briefPhoto): RedirectResponse
    {
        abort_unless($briefPhoto->brief->wedding_id === $wedding->id, 404);

        $briefPhoto->delete();

        return back();
    }
}

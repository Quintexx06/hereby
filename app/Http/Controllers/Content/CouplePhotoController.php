<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\StoreCouplePhotoRequest;
use App\Models\Wedding;
use App\Models\WeddingPhoto;
use App\Support\WebImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

/**
 * «Inhalte»: the couple's own photos for the top of their site. Only a web
 * copy is kept (longest edge 1600px), so guest pages stay light (rule 2).
 */
class CouplePhotoController extends Controller
{
    public const int EDGE = 1600;

    public function store(StoreCouplePhotoRequest $request, Wedding $wedding): RedirectResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('photo');
        $copy = WebImage::jpeg((string) $file->get(), self::EDGE);

        if ($copy === null) {
            throw ValidationException::withMessages(['photo' => 'Dieses Foto können wir nicht lesen. Versucht ein anderes.']);
        }

        $path = WeddingPhoto::directoryFor($wedding->id).'/'.Str::uuid().'.jpg';
        Storage::disk(WeddingPhoto::DISK)->put($path, $copy['bytes']);

        $photo = new WeddingPhoto;
        $photo->wedding_id = $wedding->id;
        $photo->path = $path;
        $photo->width = $copy['width'];
        $photo->height = $copy['height'];
        $last = $wedding->couplePhotos()->reorder()->max('position');
        $photo->position = $last === null ? 0 : (int) $last + 1;
        $photo->save();

        return back();
    }

    /**
     * The chosen photo moves to the front; the others keep their order.
     */
    public function cover(Wedding $wedding, WeddingPhoto $couplePhoto): RedirectResponse
    {
        DB::transaction(function () use ($wedding, $couplePhoto): void {
            $order = $wedding->couplePhotos()->whereKeyNot($couplePhoto->id)->get()->prepend($couplePhoto);
            $order->each(fn (WeddingPhoto $each, int $position) => $each->update(['position' => $position]));
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Titelbild gewählt.']);

        return back();
    }

    public function destroy(Wedding $wedding, WeddingPhoto $couplePhoto): RedirectResponse
    {
        $couplePhoto->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Foto gelöscht.']);

        return back();
    }
}

<?php

namespace App\Http\Controllers\Partners;

use App\Enums\PartnerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Memories\StoreGuestPhotoRequest;
use App\Jobs\MakePhotoThumbnail;
use App\Models\GuestPhoto;
use App\Models\Wedding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Gallery delivery: the photographer uploads into the couple's
 * «Erinnerungen», marked as theirs; the photos leave with the wedding.
 */
class PhotoUploadController extends Controller
{
    public function show(Request $request, Wedding $wedding): Response
    {
        $this->authorizeUpload($request, $wedding);

        return Inertia::render('partner/Upload', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'uploaded' => $wedding->photos()->where('partner_id', $request->user()?->partner_id)->count(),
            'uploadUrl' => route('partner.photos.store', $wedding),
        ]);
    }

    /**
     * One photo per request, as for guests.
     */
    public function store(StoreGuestPhotoRequest $request, Wedding $wedding): JsonResponse
    {
        $this->authorizeUpload($request, $wedding);

        /** @var UploadedFile $file */
        $file = $request->file('photo');

        $photo = new GuestPhoto;
        $photo->wedding_id = $wedding->id;
        $photo->partner_id = $request->user()?->partner_id;
        $photo->path = (string) $file->store(GuestPhoto::directoryFor($wedding->id).'/photos', GuestPhoto::DISK);
        $photo->size = (int) $file->getSize();
        $photo->save();

        MakePhotoThumbnail::dispatch($photo);

        return response()->json(['received' => true], 201);
    }

    private function authorizeUpload(Request $request, Wedding $wedding): void
    {
        abort_unless($request->user()?->partner?->type === PartnerType::Photographer, 403);
        abort_unless($wedding->includesMemories(), 404);
    }
}

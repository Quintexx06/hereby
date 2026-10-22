<?php

namespace App\Http\Controllers\Memories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Memories\StoreGuestPhotoRequest;
use App\Jobs\MakePhotoThumbnail;
use App\Models\GuestPhoto;
use App\Models\Wedding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Fotos von allen»: a guest opens the wedding's upload link on the phone,
 * picks photos and sends them. No account, no app (product rule 1).
 */
class GuestUploadController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        return Inertia::render('invitation/Memories', [
            'wedding' => [
                'coupleNames' => $wedding->couple_names,
                'template' => $wedding->template,
            ],
            'uploadUrl' => route('invitation.memories.store', $wedding),
        ]);
    }

    /**
     * One photo per request, so a weak connection loses one photo, not all.
     */
    public function store(StoreGuestPhotoRequest $request, Wedding $wedding): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('photo');

        $photo = new GuestPhoto;
        $photo->wedding_id = $wedding->id;
        $photo->path = (string) $file->store(GuestPhoto::directoryFor($wedding->id).'/photos', GuestPhoto::DISK);
        $photo->size = (int) $file->getSize();
        $photo->save();

        MakePhotoThumbnail::dispatch($photo);

        return response()->json(['received' => true], 201);
    }
}

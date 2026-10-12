<?php

namespace App\Http\Controllers\Memories;

use App\Http\Controllers\Controller;
use App\Models\GuestPhoto;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The couple's «Erinnerungen»: the upload link to share and every photo
 * their guests sent. Without Atelier the page shows what it would be.
 */
class MemoriesController extends Controller
{
    public const int PER_PAGE = 48;

    public function index(Wedding $wedding): Response
    {
        $unlocked = $wedding->includesMemories();
        $photos = $unlocked ? $wedding->photos()->latest('id')->paginate(self::PER_PAGE) : null;

        return Inertia::render('memories/Index', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $unlocked,
            'uploadLink' => $unlocked ? route('invitation.memories.show', $wedding->memoriesToken()) : null,
            'total' => $photos?->total() ?? 0,
            'photos' => collect($photos?->items() ?? [])->map(fn (GuestPhoto $photo): array => [
                'id' => $photo->id,
                'thumbnail' => route('weddings.memories.photo', [$wedding, $photo, 'klein']),
                'original' => route('weddings.memories.photo', [$wedding, $photo, 'original']),
                'sent_at' => $photo->created_at?->toIso8601String(),
            ]),
            'pages' => ['previous' => $photos?->previousPageUrl(), 'next' => $photos?->nextPageUrl()],
        ]);
    }

    /**
     * Photos are private files: they leave the disk only through this route,
     * for the couple (and the team).
     */
    public function photo(Wedding $wedding, GuestPhoto $photo, string $size): StreamedResponse
    {
        $path = $size === 'klein' ? ($photo->thumbnail_path ?? $photo->path) : $photo->path;

        return Storage::disk(GuestPhoto::DISK)->response($path, null, ['Cache-Control' => 'private, max-age=3600']);
    }

    public function destroy(Wedding $wedding, GuestPhoto $photo): RedirectResponse
    {
        $photo->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Foto gelöscht.']);

        return back();
    }
}

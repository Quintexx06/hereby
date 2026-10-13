<?php

namespace App\Http\Controllers\Witness;

use App\Http\Controllers\Controller;
use App\Models\GuestPhoto;
use App\Models\NewspaperArticle;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The witnesses edit the newspaper: remove what doesn't belong, then print
 * it in the couple's names (spec 2026-10-13). No PDF library: the browser.
 */
class NewspaperController extends Controller
{
    public function photo(Wedding $wedding, NewspaperArticle $newspaperArticle): StreamedResponse
    {
        abort_if($newspaperArticle->photo_path === null, 404);

        return Storage::disk(GuestPhoto::DISK)->response($newspaperArticle->photo_path, null, ['Cache-Control' => 'private, max-age=3600']);
    }

    public function destroy(Wedding $wedding, NewspaperArticle $newspaperArticle): RedirectResponse
    {
        $newspaperArticle->delete();

        return back();
    }

    public function print(Wedding $wedding): Response
    {
        $token = $wedding->witnessToken();

        return response()
            ->view('witness.newspaper', [
                'wedding' => $wedding,
                'articles' => $wedding->newspaperArticles,
                'photoUrl' => fn (NewspaperArticle $article): ?string => $article->photo_path
                    ? route('witness.newspaper.photo', [$token, $article])
                    : null,
            ])
            ->header('Cache-Control', 'no-store, private');
    }
}

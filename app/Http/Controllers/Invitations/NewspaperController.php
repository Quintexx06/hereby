<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitations\StoreNewspaperArticleRequest;
use App\Models\GuestPhoto;
use App\Models\NewspaperArticle;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Hochzeitszeitung»: friends send a story (and a photo) through one link.
 * No account; the witnesses see it, the couple reads it in print.
 */
class NewspaperController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        return Inertia::render('invitation/Newspaper', [
            'wedding' => ['coupleNames' => $wedding->couple_names, 'theme' => $wedding->theme, 'accentColor' => $wedding->accent_color],
            'storeUrl' => route('invitation.newspaper.store', $wedding->newspaperToken()),
            'sent' => (bool) session('contributed'),
        ]);
    }

    public function store(StoreNewspaperArticleRequest $request, Wedding $wedding): RedirectResponse
    {
        $article = new NewspaperArticle($request->safe()->only(['author_name', 'title', 'body']));
        $article->wedding_id = $wedding->id;
        $article->position = (int) $wedding->newspaperArticles()->max('position') + 1;

        $photo = $request->file('photo');
        if ($photo instanceof UploadedFile) {
            $article->photo_path = (string) $photo->store(GuestPhoto::directoryFor($wedding->id).'/newspaper', GuestPhoto::DISK);
        }

        $article->save();

        return to_route('invitation.newspaper.show', $wedding->newspaperToken())->with('contributed', true);
    }
}

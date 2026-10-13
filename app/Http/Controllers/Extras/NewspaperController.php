<?php

namespace App\Http\Controllers\Extras;

use App\Enums\Addon;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Hochzeitszeitung» for the couple: the links to share, and how many
 * stories came in. What they say stays a surprise until it is printed.
 */
class NewspaperController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        $unlocked = $wedding->hasAddon(Addon::Newspaper);

        return Inertia::render('extras/Newspaper', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $unlocked,
            'articles' => $unlocked ? $wedding->newspaperArticles()->count() : 0,
            'contributeLink' => $unlocked ? route('invitation.newspaper.show', $wedding->newspaperToken()) : null,
            'witnessLink' => $unlocked ? route('witness.show', $wedding->witnessToken()) : null,
        ]);
    }
}

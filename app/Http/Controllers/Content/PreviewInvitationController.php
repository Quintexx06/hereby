<?php

namespace App\Http\Controllers\Content;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use App\Support\WeddingLanguages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The couple sees their invitation as a guest would, in any of their
 * languages, for a sample household invited to everything. Nothing is
 * saved and no household counts as opened.
 */
class PreviewInvitationController extends Controller
{
    public function __invoke(Request $request, Wedding $wedding): Response
    {
        $languages = WeddingLanguages::of($wedding);
        $locale = Locale::tryFrom($request->string('sprache')->value()) ?? $wedding->default_locale;
        $locale = in_array($locale->value, $languages, true) ? $locale : $wedding->default_locale;
        App::setLocale($locale->value);

        $wedding->load('contentBlocks');
        $household = new Household(['name' => $wedding->couple_names, 'locale' => $locale, 'plus_one_allowed' => false]);
        $household->setRelation('wedding', $wedding);
        $household->setRelation('events', $wedding->events()->orderBy('starts_at')->get());
        $household->setRelation('guests', collect([
            (new Guest(['first_name' => $wedding->partner_one ?? $wedding->couple_names]))->setRelation('responses', collect()),
        ]));

        return Inertia::render('invitation/Show', [
            'invitation' => new InvitationResource($household),
            'replied' => false,
            'preview' => true,
            'previewLanguages' => $languages,
        ]);
    }
}

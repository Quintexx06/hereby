<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Household;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The guest-facing personal link: one household, its own events, its language.
 */
class ShowInvitationController extends Controller
{
    public function __invoke(Household $household): Response
    {
        App::setLocale($household->locale->value);

        $household->markOpened();
        $household->load(['wedding', 'guests', 'events']);

        return Inertia::render('invitation/Show', [
            'invitation' => new InvitationResource($household),
        ]);
    }
}

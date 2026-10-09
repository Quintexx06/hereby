<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Household;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The guest-facing personal link: one household, its own events, its language.
 */
class ShowInvitationController extends Controller
{
    public function __invoke(Household $household): Response
    {
        $household->markOpened();
        $household->load(['wedding.contentBlocks', 'guests.responses', 'events']);

        return Inertia::render('invitation/Show', [
            'invitation' => new InvitationResource($household),
            'replied' => (bool) session('replied'),
            'preview' => false,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplyFormResource;
use App\Models\Household;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The reply form on the personal link: only this household, only its events.
 */
class ShowReplyController extends Controller
{
    public function __invoke(Household $household): Response
    {
        $household->markOpened();
        $household->load(['wedding', 'guests.responses', 'events']);

        return Inertia::render('invitation/Reply', [
            'reply' => new ReplyFormResource($household),
        ]);
    }
}

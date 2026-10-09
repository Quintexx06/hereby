<?php

namespace App\Http\Controllers\Invitations;

use App\Actions\Invitations\SaveReply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invitations\SaveReplyRequest;
use App\Models\Household;
use Illuminate\Http\RedirectResponse;

/**
 * A household sends its answer, then lands on its invitation with a thank-you.
 */
class SaveReplyController extends Controller
{
    public function __invoke(SaveReplyRequest $request, Household $household, SaveReply $save): RedirectResponse
    {
        $household->load(['wedding', 'guests', 'events']);
        $save->handle($household, $request->reply());

        return to_route('invitation.show', $household)->with('replied', true);
    }
}

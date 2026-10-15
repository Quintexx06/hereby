<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Partners\InvitePartner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\StorePartnerInviteRequest;
use App\Models\Partner;
use App\Models\PartnerInvite;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 * «Partner einladen»: the couple sends their partner a link to join, and
 * can take an unused invitation back.
 */
class WeddingPartnerInvitesController extends Controller
{
    public function store(StorePartnerInviteRequest $request, Wedding $wedding, InvitePartner $invite): RedirectResponse
    {
        /** @var array{type: string, name: string, email: string} $data */
        $data = $request->validated();
        $result = $invite->handle($wedding, $data);

        Inertia::flash('toast', ['type' => 'success', 'message' => $result instanceof Partner
            ? "{$result->name} ist schon bei Hereby und jetzt verbunden."
            : "Die Einladung an {$result->name} ist unterwegs."]);

        return back();
    }

    public function destroy(Wedding $wedding, PartnerInvite $invite): RedirectResponse
    {
        abort_unless($invite->wedding_id === $wedding->id, 404);

        $invite->delete();

        return back();
    }
}

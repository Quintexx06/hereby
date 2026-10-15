<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\UpdatePartnerProfileRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Profil»: entered once. A venue's address, travel and rooms fill every
 * couple's site that connects it (ConnectPartner).
 */
class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        /** @var Partner $partner */
        $partner = $request->user()?->partner;

        return Inertia::render('partner/Profile', [
            'isVenue' => $partner->isVenue(),
            'profile' => $partner->only(['name', 'email', 'phone', 'website', 'address', 'postcode', 'town', 'capacity', 'about', 'travel', 'stay']),
        ]);
    }

    public function update(UpdatePartnerProfileRequest $request): RedirectResponse
    {
        $request->user()?->partner?->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Profil gespeichert.']);

        return back();
    }
}

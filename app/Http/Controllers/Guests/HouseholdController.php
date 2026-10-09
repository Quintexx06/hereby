<?php

namespace App\Http\Controllers\Guests;

use App\Actions\Guests\UpdateHousehold;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guests\UpdateHouseholdRequest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 * Editing, removing and re-issuing the link of one household. Routes are
 * scoped, so a household is only reachable through its own wedding.
 */
class HouseholdController extends Controller
{
    public function update(UpdateHouseholdRequest $request, Wedding $wedding, Household $household, UpdateHousehold $update): RedirectResponse
    {
        $update->handle($household, $request->household());

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$household->name} gespeichert."]);

        return back();
    }

    public function destroy(Wedding $wedding, Household $household): RedirectResponse
    {
        $household->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$household->name} entfernt."]);

        return to_route('weddings.guests.index', $wedding);
    }

    /**
     * A fresh link for a household whose link went to the wrong person.
     * The old link stops working at once (ADR 0005).
     */
    public function renewLink(Wedding $wedding, Household $household): RedirectResponse
    {
        $household->forceFill([
            'token' => $household->newUniqueId(),
            'opened_at' => null,
        ])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Neuer Link erstellt. Der alte funktioniert nicht mehr.']);

        return back();
    }
}

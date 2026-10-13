<?php

namespace App\Http\Controllers\Evening;

use App\Enums\ProgrammeStatus;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Einlagen» for the couple: the witness link to share, a time budget, and
 * only counts; what is planned stays a surprise (spec 2026-10-13).
 */
class ProgrammeController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        $unlocked = $wedding->includesEvening();
        $items = $wedding->programmeItems()->get(['minutes', 'status']);
        $accepted = $items->where('status', ProgrammeStatus::Accepted);

        return Inertia::render('evening/Programme', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $unlocked,
            'witnessLink' => $unlocked ? route('witness.show', $wedding->witnessToken()) : null,
            'summary' => [
                'total' => $items->count(),
                'accepted' => $accepted->count(),
                'minutes' => (int) $accepted->sum('minutes'),
                'budget' => $wedding->programme_minutes,
            ],
        ]);
    }

    public function update(Request $request, Wedding $wedding): RedirectResponse
    {
        Gate::authorize('useEvening', $wedding);

        $validated = $request->validate(['programme_minutes' => ['nullable', 'integer', 'min:5', 'max:600']]);
        $wedding->forceFill(['programme_minutes' => $validated['programme_minutes'] ?? null])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Zeitrahmen gespeichert.']);

        return back();
    }

    /**
     * A new witness link, when the old one went to the wrong person.
     */
    public function renew(Wedding $wedding): RedirectResponse
    {
        Gate::authorize('useEvening', $wedding);

        $wedding->renewWitnessToken();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Neuer Link erstellt. Der alte funktioniert nicht mehr.']);

        return back();
    }
}

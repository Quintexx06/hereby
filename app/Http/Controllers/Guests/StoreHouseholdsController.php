<?php

namespace App\Http\Controllers\Guests;

use App\Actions\Guests\ImportHouseholds;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guests\StoreHouseholdsRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 * Saves the confirmed import, or a single household added by hand.
 */
class StoreHouseholdsController extends Controller
{
    public function __invoke(StoreHouseholdsRequest $request, Wedding $wedding, ImportHouseholds $import): RedirectResponse
    {
        $count = $import->handle($wedding, $request->validated('households'));

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $count === 1 ? 'Haushalt hinzugefügt.' : "{$count} Haushalte hinzugefügt.",
        ]);

        return to_route('weddings.guests.index', $wedding);
    }
}

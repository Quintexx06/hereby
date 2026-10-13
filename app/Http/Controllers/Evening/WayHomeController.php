<?php

namespace App\Http\Controllers\Evening;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evening\UpdateWayHomeRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Heimweg»: the couple lists the night's last connections; guests see
 * them with a countdown and get a mail half an hour before each.
 */
class WayHomeController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        return Inertia::render('evening/WayHome', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $wedding->includesEvening(),
            'connections' => $wedding->way_home ?? [],
        ]);
    }

    public function update(UpdateWayHomeRequest $request, Wedding $wedding): RedirectResponse
    {
        Gate::authorize('useEvening', $wedding);

        $connections = $request->connections();
        $wedding->forceFill(['way_home' => $connections === [] ? null : $connections])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Heimweg gespeichert.']);

        return back();
    }
}

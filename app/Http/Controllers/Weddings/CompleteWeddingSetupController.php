<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Weddings\CompleteWeddingSetup;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

/**
 * "Website erstellen": the last step of the setup.
 */
class CompleteWeddingSetupController extends Controller
{
    public function __invoke(Wedding $wedding, CompleteWeddingSetup $complete): RedirectResponse
    {
        Gate::authorize('update', $wedding);

        $missing = $complete->handle($wedding);

        if ($missing) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Hier fehlt noch etwas.']);

            return to_route('weddings.setup.show', [$wedding, $missing]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Eure Website steht. Jetzt kommen die Gäste.']);

        return to_route('dashboard');
    }
}

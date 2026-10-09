<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Weddings\StartWeddingSetup;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * "Hochzeit einrichten": opens the couple's draft, new or resumed.
 */
class StartWeddingSetupController extends Controller
{
    public function __invoke(Request $request, StartWeddingSetup $start): RedirectResponse
    {
        $wedding = $start->handle($request->user());

        return to_route('weddings.setup.show', [$wedding, $wedding->setup_step]);
    }
}

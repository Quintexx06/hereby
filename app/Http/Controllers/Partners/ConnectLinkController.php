<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * A partner's link for couples (`/verbinden/KAST-7Q2M`). Signed out, the
 * login keeps it as the intended page; signed in, the couple lands on
 * «Partner» with the code filled in, one click from connecting.
 */
class ConnectLinkController extends Controller
{
    public function __invoke(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();
        $wedding = $user?->weddings()->first();

        if ($user?->partner_id || ! $wedding) {
            return to_route('dashboard');
        }

        return to_route('weddings.partners.index', ['wedding' => $wedding, 'code' => $code]);
    }
}

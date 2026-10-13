<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * The team marks a couple's own domain as connected once DNS and TLS work;
 * from then on every personal link uses it (spec 2026-10-13).
 */
class ConnectDomainController extends Controller
{
    public function __invoke(Request $request, Wedding $wedding): RedirectResponse
    {
        $connected = $request->validate(['connected' => ['required', 'boolean']])['connected'];
        abort_if($connected && $wedding->custom_domain === null, 422, 'No domain to connect.');

        $wedding->forceFill(['custom_domain_verified_at' => $connected ? now() : null])->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => $connected ? 'Domain verbunden.' : 'Domain getrennt.']);

        return back();
    }
}

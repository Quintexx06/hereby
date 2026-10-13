<?php

namespace App\Http\Controllers\Extras;

use App\Actions\Billing\ConfirmCheckout;
use App\Actions\Billing\StartCheckout;
use App\Enums\Addon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Extras\BuyAddonRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * «Extras»: what a couple can add to their plan, one by one (spec 2026-10-13).
 */
class ExtrasController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        $wedding->load('addons');

        return Inertia::render('extras/Index', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'plan' => $wedding->plan?->value,
                'evening' => $wedding->includesEvening(),
                'memories_kept_until' => $wedding->memories_kept_until?->toDateString(),
            ],
            'addons' => collect(Addon::cases())->map(fn (Addon $addon): array => [
                'key' => $addon->value,
                'price' => intdiv($addon->price(), 100),
                'owned' => $wedding->hasAddon($addon),
                'included' => $addon->includedIn($wedding->plan),
                'buyable' => $wedding->canBuy($addon),
            ])->all(),
            'checkoutReady' => filled(config('services.stripe.secret')),
        ]);
    }

    public function store(BuyAddonRequest $request, Wedding $wedding, StartCheckout $checkout): HttpResponse
    {
        abort_unless(filled(config('services.stripe.secret')), 503, 'Payments are not configured.');

        return Inertia::location($checkout->forAddon($wedding, $request->addon()));
    }

    /**
     * Stripe sends the couple back here; the session is checked at Stripe.
     */
    public function confirm(Request $request, Wedding $wedding, ConfirmCheckout $confirm): RedirectResponse
    {
        $sessionId = $request->string('session_id')->toString();
        $paid = $sessionId !== '' && $confirm->fromSessionId($wedding, $sessionId);

        Inertia::flash('toast', $paid
            ? ['type' => 'success', 'message' => 'Danke! Euer Extra ist freigeschaltet.']
            : ['type' => 'info', 'message' => 'Die Zahlung ist noch nicht bestätigt. Wir schalten frei, sobald sie da ist.']);

        return to_route('weddings.extras.show', $wedding);
    }
}

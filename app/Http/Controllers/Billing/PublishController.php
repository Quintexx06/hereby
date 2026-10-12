<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\ConfirmCheckout;
use App\Actions\Billing\StartCheckout;
use App\Enums\Plan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StartCheckoutRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * «Einladungen versenden»: the paywall between a finished site and its
 * personal links. Building is free; a plan opens the links (spec 2026-10-12).
 */
class PublishController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        return Inertia::render('weddings/Publish', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'plan' => $wedding->plan?->value,
                'published' => $wedding->isPublished(),
                'active' => ! $wedding->isDraft(),
            ],
            'plans' => collect(Plan::cases())->map(fn (Plan $plan): array => [
                'key' => $plan->value,
                'price' => intdiv($plan->price(), 100),
                'due' => intdiv($wedding->amountFor($plan), 100),
                'owned' => $wedding->plan !== null && $wedding->plan->rank() >= $plan->rank(),
                'online' => $plan->isSoldOnline(),
            ])->all(),
            'checkoutReady' => filled(config('services.stripe.secret')),
        ]);
    }

    public function store(StartCheckoutRequest $request, Wedding $wedding, StartCheckout $checkout): HttpResponse
    {
        abort_if($wedding->isDraft(), 403, 'Finish the setup first.');
        abort_unless(filled(config('services.stripe.secret')), 503, 'Payments are not configured.');

        return Inertia::location($checkout->handle($wedding, $request->plan()));
    }

    /**
     * Stripe sends the couple back here; the session is checked at Stripe
     * before anything unlocks (the webhook may already have done it).
     */
    public function confirm(Request $request, Wedding $wedding, ConfirmCheckout $confirm): RedirectResponse
    {
        $sessionId = $request->string('session_id')->toString();
        $paid = $sessionId !== '' && $confirm->fromSessionId($wedding, $sessionId);

        Inertia::flash('toast', $paid
            ? ['type' => 'success', 'message' => 'Vorhang auf. Eure persönlichen Links sind jetzt offen.']
            : ['type' => 'info', 'message' => 'Die Zahlung ist noch nicht bestätigt. Wir schalten frei, sobald sie da ist.']);

        return to_route($paid ? 'weddings.guests.index' : 'weddings.publish.show', $wedding);
    }
}

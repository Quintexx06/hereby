<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\ConfirmCheckout;
use App\Actions\Billing\VerifyStripeSignature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Stripe's word that a Checkout was paid; unlocks the plan even if the
 * couple closed the tab before coming back. Signed, so it can be trusted.
 */
class StripeWebhookController extends Controller
{
    /** Events that may carry a finished payment (TWINT settles async). */
    private const array PAID_EVENTS = ['checkout.session.completed', 'checkout.session.async_payment_succeeded'];

    public function __invoke(Request $request, VerifyStripeSignature $verify, ConfirmCheckout $confirm): Response
    {
        $payload = $request->getContent();

        if (! $verify->handle($payload, $request->header('Stripe-Signature'), (string) config('services.stripe.webhook_secret'))) {
            return response('Invalid signature.', 400);
        }

        $event = json_decode($payload, true);
        $session = data_get($event, 'data.object');

        if (in_array(data_get($event, 'type'), self::PAID_EVENTS, true) && is_array($session)) {
            $confirm->fromSession($session);
        }

        return response('', 204);
    }
}

<?php

namespace App\Actions\Billing;

use App\Enums\Plan;
use App\Models\Wedding;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Opens a Stripe Checkout page for a plan (or the upgrade to it), one-time,
 * in CHF. Stripe offers the methods enabled in its dashboard (cards, TWINT).
 */
class StartCheckout
{
    /**
     * @return string The Checkout URL to send the couple to.
     */
    public function handle(Wedding $wedding, Plan $plan): string
    {
        $amount = $wedding->amountFor($plan);

        if ($amount <= 0 || ! $plan->isSoldOnline()) {
            throw new RuntimeException("Plan {$plan->value} cannot be bought for wedding {$wedding->id}.");
        }

        $returnUrl = route('weddings.publish.return', $wedding).'?session_id={CHECKOUT_SESSION_ID}';

        $session = Http::withToken(config()->string('services.stripe.secret'))
            ->asForm()
            ->post(config()->string('services.stripe.api_url').'/checkout/sessions', [
                'mode' => 'payment',
                'locale' => 'de',
                'client_reference_id' => (string) $wedding->id,
                'customer_email' => $wedding->owner->email,
                'success_url' => $returnUrl,
                'cancel_url' => route('weddings.publish.show', $wedding),
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'chf',
                        'unit_amount' => $amount,
                        'product_data' => ['name' => "Hereby {$plan->label()} · {$wedding->couple_names}"],
                    ],
                ]],
                'metadata' => ['wedding_id' => $wedding->id, 'plan' => $plan->value],
            ])
            ->throw()
            ->json();

        return (string) data_get($session, 'url');
    }
}

<?php

namespace App\Actions\Billing;

use App\Enums\Plan;
use App\Models\Wedding;

/**
 * Records a paid plan and opens the personal links. Safe to call twice
 * (the webhook and the return page both confirm), and never downgrades.
 */
class MarkWeddingPaid
{
    /**
     * @param  string|null  $paymentIntent  Stripe's payment id; null for invoices.
     */
    public function handle(Wedding $wedding, Plan $plan, ?string $paymentIntent = null): Wedding
    {
        if ($wedding->plan !== null && $wedding->plan->rank() >= $plan->rank()) {
            return $wedding;
        }

        $wedding->forceFill([
            'plan' => $plan,
            'paid_at' => $wedding->paid_at ?? now(),
            'stripe_payment_intent' => $paymentIntent ?? $wedding->stripe_payment_intent,
        ])->save();

        return $wedding;
    }
}

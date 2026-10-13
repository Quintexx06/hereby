<?php

namespace App\Actions\Billing;

use App\Enums\Addon;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

/**
 * Records a paid extra. Safe to call twice for one Checkout session (the
 * webhook and the return page both confirm); «Erinnerungen behalten»
 * adds a year per session.
 */
class MarkAddonPaid
{
    public function handle(Wedding $wedding, Addon $addon, string $session, ?string $paymentIntent = null): void
    {
        DB::transaction(function () use ($wedding, $addon, $session, $paymentIntent): void {
            if ($session !== '' && $wedding->addons()->where('stripe_session', $session)->lockForUpdate()->exists()) {
                return;
            }

            $wedding->addons()->make()->forceFill([
                'addon' => $addon,
                'stripe_session' => $session !== '' ? $session : null,
                'stripe_payment_intent' => $paymentIntent,
            ])->save();

            if ($addon === Addon::KeepMemories) {
                $wedding->keepMemoriesAnotherYear();
            }
        });
    }
}

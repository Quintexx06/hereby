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
    public function handle(Wedding $wedding, Plan $plan): Wedding
    {
        if ($wedding->plan !== null && $wedding->plan->rank() >= $plan->rank()) {
            return $wedding;
        }

        $wedding->forceFill([
            'plan' => $plan,
            'paid_at' => $wedding->paid_at ?? now(),
        ])->save();

        return $wedding;
    }
}

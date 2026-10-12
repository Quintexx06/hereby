<?php

namespace App\Actions\Billing;

use App\Enums\Plan;
use App\Models\Wedding;
use Illuminate\Support\Facades\Http;

/**
 * Settles a finished Checkout session, from the webhook or the return page:
 * paid sessions for this wedding unlock their plan; anything else does nothing.
 */
class ConfirmCheckout
{
    public function __construct(private MarkWeddingPaid $markPaid) {}

    /**
     * Looks the session up at Stripe; the return URL alone proves nothing.
     */
    public function fromSessionId(Wedding $wedding, string $sessionId): bool
    {
        $session = Http::withToken(config()->string('services.stripe.secret'))
            ->get(config()->string('services.stripe.api_url').'/checkout/sessions/'.rawurlencode($sessionId))
            ->throw()
            ->json();

        return is_array($session) && $this->fromSession($session, $wedding);
    }

    /**
     * @param  array<string, mixed>  $session  A Checkout session object.
     */
    public function fromSession(array $session, ?Wedding $expected = null): bool
    {
        $weddingId = (int) data_get($session, 'metadata.wedding_id');
        $plan = Plan::tryFrom((string) data_get($session, 'metadata.plan'));

        if (data_get($session, 'payment_status') !== 'paid' || $plan === null) {
            return false;
        }

        if ($expected !== null && $expected->id !== $weddingId) {
            return false;
        }

        $wedding = $expected ?? Wedding::query()->find($weddingId);

        if ($wedding === null) {
            return false;
        }

        $this->markPaid->handle($wedding, $plan);

        return true;
    }
}

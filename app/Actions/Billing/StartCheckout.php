<?php

namespace App\Actions\Billing;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\Wedding;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * Opens a Stripe Checkout page for a plan (or the upgrade to it) or an extra, one-time,
 * in CHF. Only Swiss payment methods are offered (services.stripe.payment_methods:
 * cards, which bring Apple Pay and Google Pay, and TWINT), whatever else the
 * Stripe dashboard has switched on (Klarna, Link, Bancontact and the like).
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

        return $this->open($wedding, $amount, $plan->label(), $plan->value, ['plan' => $plan->value],
            route('weddings.publish.return', $wedding), route('weddings.publish.show', $wedding));
    }

    /**
     * An extra on top of the plan (spec 2026-10-13), at its own price.
     *
     * @return string The Checkout URL to send the couple to.
     */
    public function forAddon(Wedding $wedding, Addon $addon): string
    {
        if (! $wedding->canBuy($addon)) {
            throw new RuntimeException("Add-on {$addon->value} cannot be bought for wedding {$wedding->id}.");
        }

        return $this->open($wedding, $addon->price(), $addon->label(), $addon->value, ['addon' => $addon->value],
            route('weddings.extras.return', $wedding), route('weddings.extras.show', $wedding));
    }

    /**
     * @param  array<string, string>  $metadata  What the payment unlocks.
     */
    private function open(Wedding $wedding, int $amount, string $label, string $productKey, array $metadata, string $returnUrl, string $cancelUrl): string
    {
        // One key for every attempt: a retry after a lost answer returns the same session.
        $session = self::client()
            ->withHeaders(['Idempotency-Key' => (string) Str::uuid()])
            ->retry(2, 300, fn (Throwable $error): bool => $error instanceof ConnectionException
                || ($error instanceof RequestException && $error->response->serverError()))
            ->asForm()
            ->post(config()->string('services.stripe.api_url').'/checkout/sessions', [
                'mode' => 'payment',
                'locale' => 'de',
                'client_reference_id' => (string) $wedding->id,
                'customer_email' => $wedding->owner->email,
                'success_url' => $returnUrl.'?session_id={CHECKOUT_SESSION_ID}',
                // A receipt with an invoice PDF for the couple; codes for venue partners.
                'invoice_creation' => ['enabled' => 'true'],
                'allow_promotion_codes' => 'true',
                // Hereby is the seller, not Stripe as merchant of record (which has no TWINT).
                'managed_payments' => ['enabled' => 'false'],
                'payment_method_types' => self::paymentMethods(),
                'payment_intent_data' => ['description' => "Hereby {$label} · {$wedding->couple_names}"],
                'cancel_url' => $cancelUrl,
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'chf',
                        'unit_amount' => $amount,
                        ...self::product($wedding, $productKey, $label),
                    ],
                ]],
                'metadata' => ['wedding_id' => $wedding->id, ...$metadata],
            ])
            ->throw()
            ->json();

        return (string) data_get($session, 'url');
    }

    /**
     * The plan's or extra's product in the Stripe catalogue, so sales report
     * per product; without one, a product made up for this payment.
     *
     * @return array{product: string}|array{product_data: array{name: string}}
     */
    private static function product(Wedding $wedding, string $key, string $label): array
    {
        $product = config("services.stripe.products.{$key}");

        return is_string($product) && $product !== ''
            ? ['product' => $product]
            : ['product_data' => ['name' => "Hereby {$label} · {$wedding->couple_names}"]];
    }

    /**
     * The payment methods Checkout may show.
     *
     * @return list<string>
     */
    private static function paymentMethods(): array
    {
        $methods = array_values(array_filter(array_map('trim', explode(',', (string) config('services.stripe.payment_methods')))));

        return $methods !== [] ? $methods : ['card', 'twint'];
    }

    /**
     * Stripe's API, authenticated, on the pinned API version if one is set.
     */
    public static function client(): PendingRequest
    {
        return Http::withToken(config()->string('services.stripe.secret'))
            ->withHeaders(array_filter(['Stripe-Version' => config('services.stripe.api_version')]))
            ->timeout(15);
    }
}

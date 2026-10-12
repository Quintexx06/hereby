<?php

namespace App\Actions\Billing;

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
                'success_url' => $returnUrl,
                // A receipt with an invoice PDF for the couple; codes for venue partners.
                'invoice_creation' => ['enabled' => 'true'],
                'allow_promotion_codes' => 'true',
                // Hereby is the seller, not Stripe as merchant of record (which has no TWINT).
                'managed_payments' => ['enabled' => 'false'],
                'payment_intent_data' => ['description' => "Hereby {$plan->label()} · {$wedding->couple_names}"],
                'cancel_url' => route('weddings.publish.show', $wedding),
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'chf',
                        'unit_amount' => $amount,
                        ...self::product($wedding, $plan),
                    ],
                ]],
                'metadata' => ['wedding_id' => $wedding->id, 'plan' => $plan->value],
            ])
            ->throw()
            ->json();

        return (string) data_get($session, 'url');
    }

    /**
     * The plan's product in the Stripe catalogue, so sales report per plan;
     * without one, a product made up for this payment.
     *
     * @return array{product: string}|array{product_data: array{name: string}}
     */
    private static function product(Wedding $wedding, Plan $plan): array
    {
        $product = config("services.stripe.products.{$plan->value}");

        return is_string($product) && $product !== ''
            ? ['product' => $product]
            : ['product_data' => ['name' => "Hereby {$plan->label()} · {$wedding->couple_names}"]];
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

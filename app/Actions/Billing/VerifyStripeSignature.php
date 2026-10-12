<?php

namespace App\Actions\Billing;

/**
 * Checks a webhook's Stripe-Signature header: an HMAC-SHA256 of
 * "{timestamp}.{payload}" with the endpoint's signing secret, at most five
 * minutes old (Stripe's own scheme, so no SDK is needed).
 */
class VerifyStripeSignature
{
    public const int TOLERANCE_SECONDS = 300;

    public function handle(string $payload, ?string $header, string $secret): bool
    {
        if ($header === null || $secret === '') {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $header) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');

            if ($key === 't') {
                $timestamp = (int) $value;
            } elseif ($key === 'v1') {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || abs(time() - $timestamp) > self::TOLERANCE_SECONDS) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}

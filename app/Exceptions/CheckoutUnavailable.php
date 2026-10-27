<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Stripe could not open a Checkout page (refused, or out of reach). The
 * reason is in the team's log; the couple gets a plain message instead.
 */
class CheckoutUnavailable extends RuntimeException {}

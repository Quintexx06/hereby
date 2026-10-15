<?php

return [

    // Swiss address search (federal building address register, ADR 0010).
    'geoadmin' => [
        'search_url' => env('GEOADMIN_SEARCH_URL', 'https://api3.geo.admin.ch/rest/services/api/SearchServer'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    // Payments: Stripe Checkout, one-time, in CHF (cards and TWINT).
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'api_url' => env('STRIPE_API_URL', 'https://api.stripe.com/v1'),
        // Pin the API version the integration was tested on (empty: the account's default).
        'api_version' => env('STRIPE_API_VERSION'),
        // Payment methods Checkout offers: cards (with Apple Pay and Google Pay) and TWINT.
        'payment_methods' => env('STRIPE_PAYMENT_METHODS', 'card,twint'),
        // Catalogue products the plans are billed under (empty: named per payment).
        'products' => [
            'signature' => env('STRIPE_PRODUCT_SIGNATURE'),
            'atelier' => env('STRIPE_PRODUCT_ATELIER'),
        ],
    ],

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];

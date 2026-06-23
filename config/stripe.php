<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration — Chinese Enterprise Account
    |--------------------------------------------------------------------------
    | Stripe account registered and hosted in China (cn.stripe.com).
    | Supports CNY as primary currency for payouts.
    */

    'key'             => env('STRIPE_KEY'),
    'secret'          => env('STRIPE_SECRET'),
    'webhook_secret'  => env('STRIPE_WEBHOOK_SECRET'),
    'currency'        => env('STRIPE_CURRENCY', 'cny'),
    'account_country' => env('STRIPE_ACCOUNT_COUNTRY', 'CN'),

    /*
    | Supported card networks for Africa → China corridor
    */
    'supported_card_brands' => ['visa', 'mastercard', 'unionpay'],

    /*
    | Payment Intent configuration
    */
    'payment_intent' => [
        'capture_method'         => 'automatic',
        'confirmation_method'    => 'automatic',
        'setup_future_usage'     => 'off_session',
    ],

    /*
    | 3D Secure is required for all card payments (PSD2/security)
    */
    'require_3d_secure' => true,

    /*
    | Webhook events to process
    */
    'webhook_events' => [
        'payment_intent.succeeded',
        'payment_intent.payment_failed',
        'payment_intent.canceled',
        'charge.refunded',
        'payment_method.attached',
        'payment_method.detached',
        'customer.created',
    ],
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for Midtrans payment gateway
    | integration. Update these values with your Midtrans merchant credentials.
    |
    */

    'enabled' => env('MIDTRANS_ENABLED', true),

    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'production' => env('MIDTRANS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Midtrans API Settings
    |--------------------------------------------------------------------------
    */

    'is_production' => env('MIDTRANS_PRODUCTION', false),

    'is_sanitized' => true,

    'is_3ds' => true,

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    */

    'payment_methods' => [
        'credit_card',
        'debit_card',
        'bank_transfer',
        'e_wallet',
        'qris',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */

    'notification_url' => env('APP_URL') . '/api/payments/callback',

    'finish_redirect_url' => env('APP_URL') . '/payment/finish',

    'error_redirect_url' => env('APP_URL') . '/payment/error',

    'pending_redirect_url' => env('APP_URL') . '/payment/pending',
];

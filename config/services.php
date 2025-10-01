<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'paypal' => [
        'client_id' => config('paypal.mode') === 'live'
            ? config('paypal.live.client_id')
            : config('paypal.sandbox.client_id'),
        'currency' => config('paypal.currency', 'USD'),
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'key' => env('STRIPE_KEY'),
    ],

    'orange' => [
        'env' => env('ORANGE_MONEY_ENV', 'sandbox'),
        'base_url' => env('ORANGE_MONEY_BASE_URL'),
        'api_key' => env('ORANGE_MONEY_API_KEY'),
        'secret_key' => env('ORANGE_MONEY_SECRET_KEY'),
        'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID'),
    ],


];

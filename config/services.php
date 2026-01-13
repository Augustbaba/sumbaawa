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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'alibaba' => [
        'app_key' => env('ALIBABA_APP_KEY'),
        'app_secret' => env('ALIBABA_APP_SECRET'),
        'access_token' => env('ALIBABA_ACCESS_TOKEN'),
        'api_url' => env('ALIBABA_API_URL', 'https://gw.api.taobao.com/router/rest'),
    ],

    'paypal' => [
        'mode'    => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        'sandbox' => [
            'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
            'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
            'app_id'        => 'APP-80W284485P519543T',
        ],
        'live' => [
            'client_id'     => env('PAYPAL_LIVE_CLIENT_ID', ''),
            'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
            'app_id'        => env('PAYPAL_LIVE_APP_ID', ''),
        ],

        'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Sale, Authorization, Order
        'currency'       => env('PAYPAL_CURRENCY', 'USD'),
        'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
        'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
        'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
    ],

];

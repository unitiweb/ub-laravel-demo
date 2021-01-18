<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Plaid
    |--------------------------------------------------------------------------
    |
    | Plaid is a server to get bank account transactions, balances, and other information
    | Documentation: https://plaid.com/docs/
    |
    */

    'driver' => \App\Financials\Plaid\Plaid::class,

    'plaid' => [
        'webhook' => env('PLAID_WEBHOOK_ROUTE', '/webhook/plaid'),
        'environment' => env('PLAID_ENV', 'sandbox'),
        'version' => env('PLAID_VERSION', '2020-09-14'),
        'language' => env('PLAID_LANGUAGE', 'en'),
        'country_codes' => env('PLAID_COUNTRY_CODES', 'US'),
        'products' => [
            'auth',
            'transactions',
        ],
        'credentials' => [
            'client_id' => env('PLAID_CLIENT_ID', ''),
            'secret' => env('PLAID_SECRET', ''),
            'public_key' => env('PLAID_PUBLIC_KEY', ''),
        ],
    ],

];

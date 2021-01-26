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

    'driver' => env('FINANCIAL_DRIVER', 'plaid'),

    'plaid' => [
        'driver' => \App\Financials\Drivers\Plaid\Plaid::class,
        'config' => [
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
    ],

];

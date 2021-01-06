<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Json Web Token (JWT) configuration
    |--------------------------------------------------------------------------
    */
    'secret' => env('JWT_SECRET'),
    'algorithm' => env('JWT_ALGORITHM', 'HS256'),
    'issuer' => env('JWT_ISSUER', 'budget-api-dev'),
    'max_concurrent_logins' => env('JWT_MAX_CONCURRENT_LOGINS', 5),

    /*
    |--------------------------------------------------------------------------
    | Expiration value should be in seconds
    | Examples:
    |   - 1 hour would be (60 * 60)
    |   - 1 day would be (60 * 60 * 24)
    |--------------------------------------------------------------------------
    */
    'access_expires' => env('JWT_ACCESS_EXPIRES', 60 * 60),
    'refresh_expires' => env('JWT_REFRESH_EXPIRES', 60 * 60 * 24),

    /*
    |--------------------------------------------------------------------------
    | Validation code defaults
    | Examples:
    |   - 5 minutes would be 60 * 5
    |--------------------------------------------------------------------------
    */

    'code' => [
        'expires_at' => env('JWT_REFRESH_EXPIRES', 60 * 5),
    ],
];

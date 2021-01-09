<?php

namespace App\Exceptions\Auth;

use Exception;
use Throwable;

/**
 * Exception for when a user is not authenticated
 *
 * @package App\Exceptions\Auth
 */
class AuthenticationException extends Exception
{
    /**
     * InvalidCredentialsException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 401, Throwable $previous = null)
    {
        if (!$message) {
            $message = 'User is not authenticated';
        }

        parent::__construct($message, $code, $previous);
    }
}

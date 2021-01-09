<?php

namespace App\Exceptions\Auth;

use Exception;
use Throwable;

/**
 * Exception for when authentication token is not valid
 *
 * @package App\Exceptions\Auth
 */
class InvalidAuthTokenException extends Exception
{
    /**
     * InvalidCredentialsException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 403, Throwable $previous = null)
    {
        if (!$message) {
            $message = 'Invalid authentication token';
        }

        parent::__construct($message, $code, $previous);
    }
}

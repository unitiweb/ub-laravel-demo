<?php

namespace App\Exceptions\Auth;

use Exception;
use Throwable;

/**
 * Exception for when a user is not authenticated
 *
 * @package App\Exceptions\Auth
 */
class AuthorizationException extends Exception
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
            $message = 'This action is not authorized';
        }

        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions\Auth;

use Exception;
use Throwable;

/**
 * Exception for when authentication fails
 *
 * @package App\Exceptions\Auth
 */
class UserNotVerifiedException extends Exception
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
            $message = 'User not verified';
        }

        parent::__construct($message, $code, $previous);
    }
}

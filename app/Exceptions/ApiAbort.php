<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Exception for a general error
 * Just the code number is needed
 *
 * @package App\Exceptions\Auth
 */
class ApiAbort extends Exception
{
    /**
     * JsonAbort constructor.
     *
     * @param int $code The status code
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct(int $code, string $message = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Exception for handling unprocessable entry (422)
 *
 * @package App\Exceptions\Auth
 */
class UnprocessableEntryException extends Exception
{
    /**
     * UnprocessableEntryException constructor
     *
     * @param string|null $message
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, Throwable $previous = null)
    {
        if (!$message) {
            $message = 'The api route does not exist';
        }

        parent::__construct($message, 422, $previous);
    }
}

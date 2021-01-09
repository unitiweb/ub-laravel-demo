<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Exception for when an api route can't be found
 *
 * @package App\Exceptions\Auth
 */
class ApiRouteNotFoundException extends Exception
{
    /**
     * ApiRouteNotFoundException constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        $message = 'The api route does not exist';

        parent::__construct($message, 404, $previous);
    }
}

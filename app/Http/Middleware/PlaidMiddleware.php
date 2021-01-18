<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class PlaidMiddleware
{
    /**
     * Handle a plaid request by validating the verification code
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        if ($jwt = $request->header('plaid-verification')) {
            list($header, $payload, $signature) = explode (".", $jwt);
            $decode = base64_decode($payload);
            $decode = json_decode($decode, true);
            $code = $decode['request_body_sha256'];
            if (hash('sha256', $request->getContent()) === $code) {
                return $next($request);
            }
        }

        return response('Request not validated', 500);
    }
}

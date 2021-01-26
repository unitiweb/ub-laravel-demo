<?php

namespace App\Http\Middleware;

use App\Financials\Financial;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FinancialWebhookMiddleware
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
    public function handle(Request $request, Closure $next)
    {
        $financial = App::make(Financial::class);
        return $financial->validateWebhook($request, $next);
    }
}

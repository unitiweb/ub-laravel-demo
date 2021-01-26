<?php

namespace App\Financials;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class FinancialWebhookAbstract
{
    /**
     * Process the incoming webhook
     *
     * @param Request $request
     *
     * @return Response
     */
    abstract public function process(Request $request): Response;
}

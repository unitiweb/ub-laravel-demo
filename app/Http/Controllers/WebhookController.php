<?php

namespace App\Http\Controllers;

use App\Financials\Drivers\Plaid\Webhooks\PlaidWebhook;
use App\Financials\Financial;
use App\Jobs\FinancialSyncJob;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Controller to route all the webhook requests
 *
 * @package App\Http\Controllers
 */
class WebhookController extends Controller
{
    /**
     * Handle all the Plaid webhook requests
     *
     * @param Request $request
     * @param PlaidWebhook $webhook
     *
     * @return Response
     * @throws Exception
     */
    public function plaid(Request $request, PlaidWebhook $webhook): Response
    {
        return $webhook->process($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Financials\Drivers\Plaid\Webhooks\PlaidWebhook;
use App\Financials\Financial;
use App\Jobs\FinancialAccountsSyncJob;
use App\Jobs\FinancialSyncJob;
use App\Models\BankAccessToken;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

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
        // Validate the webhook request
        if (!PlaidWebhook::validate($request)) {
            abort(401, 'Webhook validation failed');
        }

        // Get the request data
        $data = $request->all();
        Log::debug('PlaidWebhook: request data:', $data);

        // Check for any errors in the request
        if ($error = PlaidWebhook::errorCheck($data)) {
            Log::debug('PlaidWebhook: errorCheck:' . $error);
            abort(500, $error);
        }

        // Get the bank access token for identifying what needs to be updated
        $bankAccessToken = PlaidWebhook::getAccessToken($data);
        Log::debug('PlaidWebhook: bankAccessToken:', $bankAccessToken->toArray());

        // Get the webhook type to be updated (ie: transactions)
        $type = PlaidWebhook::getHookType($data);
        Log::debug('PlaidWebhook: getHookType: ' . $type);

        switch ($type) {
            // Dispatch transactions sync
            case PlaidWebhook::TYPE_TRANSACTIONS:
                Log::debug('PlaidWebhook: TYPE_TRANSACTIONS');
                Bus::chain([
                    new FinancialAccountsSyncJob($bankAccessToken),
                ])->dispatch();
                break;
        }

        return $webhook->process($request);
    }
}

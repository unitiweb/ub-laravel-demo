<?php

namespace App\Http\Controllers;

use App\Exceptions\JsonError;
use App\Financials\Plaid\Webhooks\Webhook;
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
     * @param Webhook $webhook
     *
     * @return Response
     */
    public function plaid(Request $request, Webhook $webhook): Response
    {
        return $webhook->process($request);
    }
}

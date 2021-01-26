<?php

namespace App\Financials\Drivers\Plaid\Webhooks\Processors;

use App\Financials\Drivers\Plaid\Plaid;
use App\Financials\Financial;
use App\Models\BankAccessToken;

/**
 * Class WebhookProcessor
 *
 * @package App\Financials\Drivers\Plaid\Webhooks
 */
class WebhookProcessor
{
    /**
     * @var BankAccessToken
     */
    protected $token;

    /**
     * Plaid constructor.
     *
     * @param Financial $financial
     * @param BankAccessToken $token
     */
    public function __construct(BankAccessToken $token)
    {
        $this->token = $token;
    }
}

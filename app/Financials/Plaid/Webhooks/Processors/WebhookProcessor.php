<?php

namespace App\Financials\Plaid\Webhooks\Processors;

use App\Financials\Plaid\Plaid;
use App\Models\BankAccessToken;

/**
 * Class WebhookProcessor
 *
 * @package App\Financials\Plaid\Webhooks
 */
class WebhookProcessor
{
    /**
     * @var Plaid
     */
    private $plaid;

    /**
     * @var BankAccessToken
     */
    protected $token;

    /**
     * Plaid constructor.
     *
     * @param BankAccessToken $token
     */
    public function __construct(BankAccessToken $token)
    {
        $this->plaid = new Plaid;
        $this->token = $token;
    }
}

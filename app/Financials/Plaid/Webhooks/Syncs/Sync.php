<?php

namespace App\Financials\Plaid\Webhooks\Syncs;

use App\Financials\Plaid\Plaid;
use App\Models\BankAccessToken;

/**
 * Class AccountsSync
 *
 * @package App\Financials\Plaid\Webhooks\Sync
 */
class Sync
{
    protected $offset = 0;
    protected $continue = true;
    protected $batchSize = 10;
    protected $plaid;
    protected $token;

    public function __construct(BankAccessToken $token)
    {
        $this->plaid = new Plaid;
        $this->token = $token;
    }

    /**
     * Increment the offset
     *
     * @return void
     */
    public function incrementOffset(): void
    {
        $this->offset += $this->batchSize;
    }

    /**
     * Mark process as done
     *
     * @return void
     */
    public function done()
    {
        $this->continue = false;
    }

    /**
     * Check whether we need to continue or not
     *
     * @rreturn bool
     */
    public function continue(): bool
    {
        return $this->continue;
    }
}

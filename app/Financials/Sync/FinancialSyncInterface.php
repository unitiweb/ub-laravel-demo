<?php

namespace App\Financials\Sync;

use App\Models\BankAccessToken;

/**
 * Interface FinancialSyncInterface
 * @package App\Financials
 */
interface FinancialSyncInterface
{
    /**
     * Run a sync
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function sync(BankAccessToken $bankAccessToken): bool;
}

<?php

namespace App\Financials\Sync;

use App\Financials\Financial;
use App\Models\BankAccessToken;

/**
 * Base class for the financial sync processes
 *
 * @package App\Financials
 */
abstract class FinancialSyncAbstract
{
    /**
     * @var Financial
     */
    protected $financial;

    /**
     * @var BankAccessToken
     */
    protected $bankAccessToken;

    /**
     * FinancialInstitutionsSync constructor
     *
     * @param Financial $financial
     */
    public function __construct(Financial $financial)
    {
        $this->financial = $financial;
    }

    /**
     * Sync a user's institutions data
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    abstract public function sync(BankAccessToken $bankAccessToken): bool;
}

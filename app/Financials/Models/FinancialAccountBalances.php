<?php

namespace App\Financials\Models;

use App\Models\BankAccount;
use App\Models\BankBalance;

/**
 * Class AccountBalances
 *
 * @property string available
 * @property string current
 * @property string limit
 * @property string isoCurrencyCode
 * @property string unofficialCurrencyCode
 */
class FinancialAccountBalances extends FinancialModel
{
    /**
     * The allowed data keys and types
     * The is a key => value pair where the key is the data key and the value is the type
     *
     * Example:
     *  $key = ['title' => 'string'];
     *
     * @var array
     */
    protected $keys = [
        'available' => 'currency',
        'current' => 'currency',
        'limit' => 'currency',
        'isoCurrencyCode' => 'string',
        'unofficialCurrencyCode' => 'string',
    ];


    /**
     * Update the corresponding bankBalance model with the values of this model
     *
     * @param BankBalance $bankBalance
     *
     * @return void
     */
    public function update(BankBalance $bankBalance): void
    {
        $bankBalance->available = $this->available;
        $bankBalance->current = $this->current;
        $bankBalance->limit = $this->limit;

        // Save the changes if changes have been made
        $bankBalance->save();
    }
}

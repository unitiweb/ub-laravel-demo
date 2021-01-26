<?php

namespace App\Financials\Models;

use App\Models\BankAccount;

/**
 * Class AccountBalances
 *
 * @package App\Financials\Models
 *
 * @property string accountId
 * @property string institutionId
 * @property string mask
 * @property string name
 * @property string officialName
 * @property string subType
 * @property string type
 * @property FinancialAccountBalances balances
 */
class FinancialAccount extends FinancialModel
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
        'accountId' => 'string',
        'institutionId' => 'string',
        'mask' => 'string',
        'name' => 'string',
        'officialName' => 'string',
        'subType' => 'string',
        'type' => 'string',
        'balances' => FinancialAccountBalances::class,
    ];

    /**
     * Update the corresponding bankAccount model with the values of this model
     *
     * @param BankAccount $bankAccount
     *
     * @return void
     */
    public function update(BankAccount $bankAccount): void
    {
        $bankAccount->mask = $this->mask;
        $bankAccount->name = $this->name;
        $bankAccount->officialName = $this->officialName;
        $bankAccount->type = $this->type;
        $bankAccount->subType = $this->subType;

        // Save the changes if changes have been made
        $bankAccount->save();
    }
}

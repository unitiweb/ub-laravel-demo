<?php

namespace App\Financials\Models;

use App\Models\BankAccessToken;
use App\Models\BankTransaction;

/**
 * Class AccountBalances
 *
 * @package App\Financials\Models
 *
 * @property string accountId
 * @property string transactionId
 * @property array category
 * @property float amount
 * @property string transactionDate
 * @property string paymentChannel
 * @property string isoCurrencyCode
 * @property string name
 * @property bool pending
 */
class FinancialTransaction extends FinancialModel
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
        'transactionId' => 'string',
        'category' => 'array',
        'amount' => 'currency',
        'transactionDate' => 'date',
        'paymentChannel' => 'string',
        'isoCurrencyCode' => 'string',
        'name' => 'string',
        'pending' => 'boolean',
    ];

    /**
     * Update the corresponding bankTransfer model with the values of this model
     *
     * @param BankTransaction $bankTransaction
     *
     * @return void
     */
    public function update(BankTransaction $bankTransaction): void
    {
        $bankTransaction->category = implode('/', $this->category);
        $bankTransaction->amount = $this->amount;
        $bankTransaction->transactionDate = $this->transactionDate;
        $bankTransaction->paymentChannel = $this->paymentChannel;
        $bankTransaction->isoCurrencyCode = $this->isoCurrencyCode;
        $bankTransaction->name = $this->name;
        $bankTransaction->pending = $this->pending;

        // Save the changes if changes have been made
        $bankTransaction->save();
    }
}

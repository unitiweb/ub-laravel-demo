<?php

namespace App\Financials\Sync;

use App\Financials\Models\FinancialTransaction;
use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Support\Collection;

/**
 * Sync the account transactions
 *
 * @package App\Financials
 */
class FinancialTransactionsSync extends FinancialSyncAbstract implements FinancialSyncInterface
{
    /**
     * @var BankAccessToken
     */
    protected $bankAccessToken;

    /**
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function sync(BankAccessToken $bankAccessToken): bool
    {
        $this->bankAccessToken = $bankAccessToken;

        // Get transactions from the remote client
        $transactions = $this->financial->getTransactions($bankAccessToken);

        $bankAccounts = BankAccount::where('siteId', $bankAccessToken->siteId)
            ->where('bankAccessTokenId', $bankAccessToken->id)
            ->where('enabled', true)
            ->get();

        // Remove all transactions for bank accounts that are not enabled
        $this->cleanTransactions($bankAccounts);

        $bankAccounts->each(function ($bankAccount) use ($transactions) {
            $records = $transactions->filter(function ($transaction) use ($bankAccount) {
                return $transaction->accountId === $bankAccount->accountId;
            });
            $this->upsertTransactions($records, $bankAccount);
        });

        return true;
    }

    /**
     * Upsert the given transactions for the given bank account
     *
     * @param Collection $transactions
     * @param BankAccount $bankAccount
     *
     * @return void
     */
    public function upsertTransactions(Collection $transactions, BankAccount $bankAccount): void
    {
        $transactions->each(function ($transaction) use ($bankAccount) {
            $this->upsertTransaction($transaction, $bankAccount);
        });
    }

    /**
     * Upsert the given transaction
     *
     * @param FinancialTransaction $financialTransaction
     * @param BankAccount $bankAccount
     *
     * @return void
     */
    public function upsertTransaction(FinancialTransaction $financialTransaction, BankAccount $bankAccount): void
    {
        $bankTransaction = BankTransaction::where('bankAccountId', $bankAccount->id)
            ->where('transactionId', $financialTransaction->transactionId)
            ->first();

        if (!$bankTransaction) {
            $bankTransaction = BankTransaction::create([
                'siteId' => $this->bankAccessToken->siteId,
                'bankAccessTokenId' => $this->bankAccessToken->id,
                'bankAccountId' => $bankAccount->id,
                'transactionId' => $financialTransaction->transactionId,
            ]);
        }

        $financialTransaction->update($bankTransaction);
    }

    /**
     * Find all transactions that don't belong to a disabled account and delete them
     *
     * @param \Illuminate\Database\Eloquent\Collection $bankAccounts
     *
     * @return void
     */
    protected function cleanTransactions(\Illuminate\Database\Eloquent\Collection $bankAccounts): void
    {
        $ids = $bankAccounts->pluck('id');
        BankTransaction::where('siteId', $this->bankAccessToken->siteId)
            ->where('bankAccessTokenId', $this->bankAccessToken->id)
            ->whereNotIn('bankAccountId', $ids->toArray())
            ->delete();
    }
}

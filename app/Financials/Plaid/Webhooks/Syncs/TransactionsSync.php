<?php

namespace App\Financials\Plaid\Webhooks\Syncs;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;

/**
 * Class TransactionsSync
 * @package App\Financials\Plaid\Webhooks\Sync
 */
class TransactionsSync extends Sync
{
    const MAX_ALLOWED_RECORDS = 500;

    /**
     * @return bool
     */
    public function sync(): bool
    {
        try {
            $bankAccounts = BankAccount::where('siteId', $this->token->siteId)
                ->where('enabled', true)
                ->get();

            $bankTransaction = BankTransaction::where('siteId', $this->token->siteId)
                ->where('bankAccessTokenId', $this->token->id)
                ->whereIn('bankAccountId', $bankAccounts->pluck('id'))
                ->orderBy('transactionDate', 'asc')
                ->first();

            $lastRecordId = $bankTransaction->transactionId ?? null;
            $count = 0;

            while ($this->continue()) {
                // Get transactions from Plaid
                $results = $this->plaid->getTransactions($this->token, null, null, [
                    'count' => $this->batchSize,
                    'offset' => $this->offset,
                    'account_ids' => $bankAccounts->pluck('accountId')->toArray(),
                ]);

                // Map the accounts to the $this->accounts variable
                $results = $this->mapTransactions($results);

                $transactions = collect();
                foreach ($results as $record) {
                    $transactionId = $record->get('transactionId');
                    if ($lastRecordId && $lastRecordId === $transactionId) {
                        break;
                    }
                    $transactions->push($record);
                }

                // Process transactions
                $this->upsertTransactions($transactions, $bankAccounts);

                $count += $transactions->count();
                if ($count <= self::MAX_ALLOWED_RECORDS && $transactions->count() === $this->batchSize) {
                    $this->incrementOffset();
                } else {
                    $this->done();
                }
            }

            return true;
        } catch (Exception $ex) {
            //ToDo: Need to handle the exception
//            throw $ex;
            print_r($ex->getMessage()); exit;
//            dump($ex->getMessage());
            return false;
        }
    }

    /**
     * @param Collection $transactions
     *
     * @return Collection
     */
    protected function mapTransactions(Collection $transactions): Collection
    {
        return $transactions->map(function ($item) {
            return collect([
                'accountId' => $item['account_id'],
                'category' => $item['category'],
                'transactionId' => $item['transaction_id'],
                'amount' => $item['amount'],
                'transactionDate' => $item['date'],
                'paymentChannel' => $item['payment_channel'],
                'isoCurrencyCode' => $item['iso_currency_code'],
                'name' => $item['name'],
                'pending' => $item['pending'],
            ]);
        });
    }

    /**
     * Compare the remote transactions with the database transactions and add or remove as necessary
     *
     * @param Collection $transactions
     * @param DbCollection $bankAccounts
     *
     * @return void
     */
    protected function upsertTransactions(Collection $transactions, DbCollection $bankAccounts): void
    {
        foreach ($transactions as $transaction) {
            // Get the category if set
            $category = $transaction->get('category');
            if ($category && is_array($category) && count($category) >= 1) {
                $category = implode(':', $category);
            }

            $bankAccount = $bankAccounts->where('accountId', $transaction->get('accountId'))->first();
            $bankTransaction = BankTransaction::where('transactionId', $transaction->get('transactionId'))->first();

            if (!$bankTransaction) {
                // Create since the bank account doesn't exist
                $bankTransaction = new BankTransaction;
                $bankTransaction->siteId = $this->token->siteId;
                $bankTransaction->bankAccessTokenId = $this->token->id;
                $bankTransaction->bankAccountId = $bankAccount->id;
                $bankTransaction->transactionId = $transaction->get('transactionId');
            }

            // Update the remaining values
            $bankTransaction->category = $category;
            $bankTransaction->amount = $transaction->get('amount');
            $bankTransaction->transactionDate = $transaction->get('transactionDate');
            $bankTransaction->paymentChannel = $transaction->get('paymentChannel');
            $bankTransaction->isoCurrencyCode = $transaction->get('isoCurrencyCode');
            $bankTransaction->name = $transaction->get('name');
            $bankTransaction->pending = $transaction->get('pending');
            $bankTransaction->save();
        }
    }

    /**
     * Get the bank transaction if it exists
     *
     * @param string $transactionId
     * @param DbCollection $bankTransactions
     *
     * @return BankTransaction|null
     */
    protected function bankTransactionExists(string $transactionId, DbCollection $bankTransactions): ?BankTransaction
    {
        return $bankTransactions->where('transactionId', $transactionId)->first();
    }
}

<?php

namespace App\Financials\Drivers\Plaid\Webhooks\Processors;

use App\Jobs\FinancialSyncJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

/**
 * Handle transaction webhooks
 *
 * @package App\Financials\Drivers\Plaid\Webhooks
 */
class TransactionProcessor extends WebhookProcessor implements WebhookProcessorInterface
{
    // Types of transactions codes with the method to process it
    const CODES = [
        'INITIAL_UPDATE' => 'initialUpdate',                // Initial transactions ready
        'HISTORICAL_UPDATE' => 'historicUpdate',            // Historical transactions ready
        'DEFAULT_UPDATE' => 'defaultUpdate',                // New transactions available
        'TRANSACTIONS_REMOVED' => 'transactionsRemoved',    // Deleted transactions detected
    ];

    /**
     * Process the transaction webhook
     *
     * @param array $data
     *
     * @return void
     */
    public function process(array $data): void
    {
        foreach (self::CODES as $code => $method) {
            if ($data['webhook_code'] === $code) {
                $this->$method($data);
            }
        }
    }

    /**
     * Sync the accounts, transactions, and categories
     *
     * @return void
     */
    protected function syncTransactions(): void
    {
        FinancialSyncJob::dispatch($this->token);
    }

    /**
     * Process transactions for the initial update
     *
     * @param array $data
     *
     * @return void
     */
    protected function initialUpdate(array $data): void
    {
        Log::debug('Webhook: Transactions Processor: INITIAL_UPDATE', $data);
        $this->syncTransactions();
    }

    /**
     * Process historical transactions
     *
     * @param array $data
     *
     * @return void
     */
    protected function historicUpdate(array $data): void
    {
        Log::debug('Webhook: Transactions Processor: HISTORICAL_UPDATE', $data);
        // Since the INITIAL_UPDATE is send at the same time as the HISTORICAL_UPDATE this causes duplicate entries
        // So, we are going to not update here.
        $this->syncTransactions();
    }

    /**
     * Process standard transactions for updating
     *
     * @param array $data
     *
     * @return void
     */
    protected function defaultUpdate(array $data): void
    {
        Log::debug('Webhook: Transactions Processor: DEFAULT_UPDATE', $data);
        $this->syncTransactions();
    }

    /**
     * Process transactions that have been removed
     *
     * @param array $data
     *
     * @return void
     */
    protected function transactionsRemoved(array $data): void
    {
        Log::debug('Webhook: Transactions Processor: TRANSACTIONS_REMOVED', $data);

        // Get the transaction ids to be deleted
        $transactionIds = $data['removed_transactions'] ?? [];

        // Remove the transactions by id
        foreach ($transactionIds as $id) {
            Transaction::where('siteId', $this->token->siteId)->where('transactionId', $id)->delete();
        }
    }
}

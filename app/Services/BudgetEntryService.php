<?php

namespace App\Services;

use App\Facades\Services\AuthService;
use App\Facades\Services\StringService;
use App\Models\BankTransaction;
use App\Models\BankTransactionLink;
use App\Models\BankTransactionMatch;
use App\Models\Budget;
use App\Models\BudgetEntry;
use phpDocumentor\Reflection\Types\Collection;

/**
 * Service class for BudgetEntry tasks
 *
 * @package App\Services
 */
class BudgetEntryService
{
    /**
     * Put the current entry in the correct given order
     *
     * @param BudgetEntry $entry
     * @param int $order
     *
     * @return void
     */
    public function setOrder(BudgetEntry $entry, int $order): void
    {
        if ($order <= 0) {
            $order = 1;
        }

        $entries = BudgetEntry::where('id', '!=', $entry->id)
            ->where('budgetGroupId', $entry->budgetGroupId)
            ->orderBy('order', 'asc')
            ->get();

        $i = 1;
        foreach ($entries as $entry) {
            if ($i === $order) {
                $i++;
            }
            $entry->update(['order' => $i]);
            $i++;
        }

        if ($order > $entries->count()) {
            $order = $entries->count();
        }

        $entry->order = $order;
    }

    /**
     * Update the entry status
     * Mark true all lower statuses
     * If Cleared is true then so is goal and paid, and all other scenarios
     *
     * @param BudgetEntry $entry
     * @param array $data A data array setting either goal, paid, or cleared
     *
     * @return void
     */
    public function setStatus(BudgetEntry $entry, array $data): void
    {
        if (isset($data['cleared'])) {
            $entry->goal = true;
            $entry->paid = true;
        } elseif (isset($data['paid'])) {
            $entry->goal = true;
            $entry->cleared = false;
        } elseif (isset($data['goal'])) {
            $entry->paid = false;
            $entry->cleared = false;
        }
    }

    /**
     * Create a match for a bank entry with the given bank transaction
     *
     * @param Budget $budget
     * @param BudgetEntry $entry
     * @param BankTransaction $bankTransaction
     *
     * @return BankTransactionMatch
     */
    public function linkEntryTransaction(Budget $budget, BudgetEntry $entry, BankTransaction $bankTransaction): BankTransactionMatch
    {
        $siteId = AuthService::getSite()->id;

        // Create the match slug used to link the entry to transaction
        $match = StringService::makeSlug($entry->name, $bankTransaction->name);

        $bankTransactionMatch = BankTransactionMatch::where('siteId', AuthService::getSite()->id)
            ->where('bankAccountId', $bankTransaction->account->id)
            ->where('match', $match)
            ->first();

        // Check to see if there is already a transaction match entry
        if (!$bankTransactionMatch) {
            $bankTransactionMatch = BankTransactionMatch::create([
                'siteId' => $siteId,
                'bankAccountId' => $bankTransaction->account->id,
                'entryName' => $entry->name,
                'entryAmount' => $entry->amount,
                'transactionName' => $bankTransaction->name,
                'transactionAmount' => $bankTransaction->amount,
                'match' => $match,
            ]);
        }

        // If already linked get the linked object
        $bankTransactionLink = BankTransactionLink::where('siteId', $siteId)
            ->where('budgetId', $budget->id)
            ->where('budgetEntryId', $entry->id)
            ->first();

        // If there is not link then create one
        if (!$bankTransactionLink) {
            $bankTransactionLink = BankTransactionLink::create([
                'siteId' => $siteId,
                'budgetId' => $budget->id,
                'budgetEntryId' => $entry->id,
                'bankTransactionId' => $bankTransaction->id,
            ]);
        } else {
            // Add the transaction link
            $bankTransactionLink->bankTransactionId = $bankTransaction->id;
            $bankTransactionLink->save();
        }

        // Update the entry transaction link
        $entry->bankTransactionLinkId = $bankTransactionLink->id;
        $entry->save();

        return $bankTransactionMatch;
    }

    /**
     * Unlink a transaction for the entry
     * @param Budget $budget
     * @param BudgetEntry $entry
     *
     * @return void
     */
    public function unlinkEntryTransaction(Budget $budget, BudgetEntry $entry): void
    {
        $siteId = AuthService::getSite()->id;

        // If already linked get the linked object
        $bankTransactionLink = BankTransactionLink::where('siteId', $siteId)
            ->where('budgetId', $budget->id)
            ->where('budgetEntryId', $entry->id)
            ->first();

        $entry->bankTransactionLinkId = null;
        $entry->save();

        if ($bankTransactionLink) {
            $bankTransactionLink->delete();
        }
    }
}

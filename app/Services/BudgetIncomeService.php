<?php

namespace App\Services;

use App\Facades\Services\AuthService;
use App\Facades\Services\StringService;
use App\Models\BankTransaction;
use App\Models\BankTransactionLink;
use App\Models\BankTransactionMatch;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetIncome;

/**
 * Service class for BudgetIncome tasks
 *
 * @package App\Services
 */
class BudgetIncomeService
{
    /**
     * Create a match for a bank income with the given bank transaction
     *
     * @param Budget $budget
     * @param BudgetIncome $income
     * @param BankTransaction $bankTransaction
     *
     * @return BankTransactionMatch
     */
    public function linkTransaction(Budget $budget, BudgetIncome $income, BankTransaction $bankTransaction): BankTransactionMatch
    {
        $siteId = AuthService::getSite()->id;

        // Create the match slug used to link the income to transaction
        $match = StringService::makeSlug($income->name, $bankTransaction->name);

        $bankTransactionMatch = BankTransactionMatch::where('siteId', AuthService::getSite()->id)
            ->where('bankAccountId', $bankTransaction->account->id)
            ->where('match', $match)
            ->first();

        // Check to see if there is already a transaction match income
        if (!$bankTransactionMatch) {
            $bankTransactionMatch = BankTransactionMatch::create([
                'siteId' => $siteId,
                'bankAccountId' => $bankTransaction->account->id,
                // Entry name and amount will be used for the income's name and amount
                'entryName' => $income->name,
                'entryAmount' => $income->amount,
                'transactionName' => $bankTransaction->name,
                'transactionAmount' => $bankTransaction->amount,
                'match' => $match,
            ]);
        }

        // If already linked get the linked object
        $bankTransactionLink = BankTransactionLink::where('siteId', $siteId)
            ->where('budgetId', $budget->id)
            ->where('budgetIncomeId', $income->id)
            ->first();

        // If there is not link then create one
        if (!$bankTransactionLink) {
            $bankTransactionLink = BankTransactionLink::create([
                'siteId' => $siteId,
                'budgetId' => $budget->id,
                'budgetIncomeId' => $income->id,
                'bankTransactionId' => $bankTransaction->id,
            ]);
        } else {
            // Add the transaction link
            $bankTransactionLink->bankTransactionId = $bankTransaction->id;
            $bankTransactionLink->save();
        }

        // Update the income transaction link
        $income->bankTransactionLinkId = $bankTransactionLink->id;
        $income->save();

        return $bankTransactionMatch;
    }

    /**
     * Unlink a transaction for the income
     *
     * @param Budget $budget
     * @param BudgetIncome $income
     *
     * @return void
     */
    public function unlinkTransaction(Budget $budget, BudgetIncome $income): void
    {
        $siteId = AuthService::getSite()->id;

        // If already linked get the linked object
        $bankTransactionLink = BankTransactionLink::where('siteId', $siteId)
            ->where('budgetId', $budget->id)
            ->where('budgetIncomeId', $income->id)
            ->first();

        $income->bankTransactionLinkId = null;
        $income->save();

        if ($bankTransactionLink) {
            $bankTransactionLink->delete();
        }
    }
}

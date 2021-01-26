<?php

namespace App\Financials\Sync;

use App\Financials\Models\FinancialAccount;
use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankBalance;
use App\Models\BankInstitution;
use App\Models\BankInstitutionDetail;
use App\Models\BankTransaction;
use App\Models\Category;

/**
 * Sync the transaction categories
 *
 * @package App\Financials
 */
class FinancialCategoriesSync extends FinancialSyncAbstract implements FinancialSyncInterface
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

        $bankTransactions = BankTransaction::where('siteId', $bankAccessToken->siteId)
            ->where('bankAccessTokenId', $bankAccessToken->id)
            ->whereNull('categoryId')
            ->get();

        $bankTransactions->each(function ($bankTransaction) {
            $this->upsertCategory($bankTransaction);
        });

        return true;
    }

    public function upsertCategory(BankTransaction $bankTransaction): void
    {
        $categories = explode('/', $bankTransaction->category);

        $prev = null;
        foreach ($categories as $category) {
            if (!$cat = Category::where('name', $category)->first()) {
                $cat = Category::create([
                    'siteId' => $this->bankAccessToken->siteId,
                    'parentId' => $prev->id ?? 0,
                    'name' => $category,
                    'order' => 0,
                ]);
            }
            $prev = $cat;
        }

        if ($prev) {
            $bankTransaction->categoryId = $prev->id;
            $bankTransaction->save();
        }
    }
}

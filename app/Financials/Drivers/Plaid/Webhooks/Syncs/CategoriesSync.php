<?php

namespace App\Financials\Drivers\Plaid\Webhooks\Syncs;

use App\Models\BankTransaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoriesSync
 *
 * @package App\Financials\Drivers\Plaid\Webhooks\Sync
 */
class CategoriesSync extends Sync
{
    /**
     * @return bool
     */
    public function sync(): bool
    {
        // Get bank accounts
        $transactions = BankTransaction::where('siteId', $this->token->siteId)->whereNull('categoryId')->get();
        $categories = Category::where('siteId', $this->token->siteId)->get();

        foreach ($transactions as $transaction) {
            $category = $this->upsertCategory($transaction, $categories);
            $transaction->categoryId = $category->id;
            $transaction->category = $category->name;
            $transaction->save();
        }

        return true;
    }

    /**
     * Upsert the categories if they don't exist
     *
     * @param BankTransaction $transaction
     * @param Collection $categories
     *
     * @return Category|null
     */
    protected function upsertCategory(BankTransaction $transaction, Collection &$categories): ?Category
    {
        $last = null;
        foreach (explode(':', $transaction->category) as $category) {
            if (!$cat = $categories->where('name', $category)->first()) {
                $c = new Category;
                $c->siteId = $this->token->siteId;
                $c->parentId = $last->id ?? 0;
                $c->name = $category;
                $c->save();
                $categories->push($c);
                $last = $c;
            } else {
                $last = $cat;
            }
        }

        return $last;
    }
}

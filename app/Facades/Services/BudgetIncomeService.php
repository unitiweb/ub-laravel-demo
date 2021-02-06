<?php

namespace App\Facades\Services;

use App\Models\BankTransactionMatch;
use App\Models\Budget;
use App\Models\BudgetIncome;
use Illuminate\Support\Facades\Facade;

/**
 * BudgetEntryService facade
 *
 * @package App\Facades\Services
 *
 * @method static BankTransactionMatch linkTransaction(Budget $budget, BudgetIncome $income, int $bankTransaction)
 * @method static void unlinkTransaction(Budget $budget, BudgetIncome $income)
 */
class BudgetIncomeService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BudgetIncomeService::class;
    }
}

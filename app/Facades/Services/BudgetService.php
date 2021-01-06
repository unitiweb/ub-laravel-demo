<?php

namespace App\Facades\Services;

use App\Models\Budget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * BudgetService facade
 *
 * @package App\Facades\Services
 *
 * @method static Budget addBudget(\Carbon\Carbon $month)
 * @method static Budget duplicateBudget(int $siteId, Budget $prevBudget, Budget $budget)
 * @method static Collection getGroupsSortedList(Budget $budget)
 */
class BudgetService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BudgetService::class;
    }
}

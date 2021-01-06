<?php

namespace App\Facades\Services;

use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use Illuminate\Support\Facades\Facade;

/**
 * BudgetGroupService facade
 *
 * @package App\Facades\Services
 *
 * @method static void cleanBudgetGroups(Budget $budget)
 * @method static void setBudgetGroup(Budget $budget, BudgetEntry $entry, int $groupId)
 */
class BudgetGroupService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BudgetGroupService::class;
    }
}

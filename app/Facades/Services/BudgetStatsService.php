<?php

namespace App\Facades\Services;

use App\Http\Resources\BudgetStatsResource;
use App\Models\Budget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * AuthService facade
 *
 * @package App\Facades\Services
 *
 * @method static Collection incomes(Budget $budget)
 * @method static Collection totals(Budget $budget)
 */
class BudgetStatsService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BudgetStatsService::class;
    }
}

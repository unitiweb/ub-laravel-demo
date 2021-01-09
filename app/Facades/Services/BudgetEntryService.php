<?php

namespace App\Facades\Services;

use App\Models\BudgetEntry;
use Illuminate\Support\Facades\Facade;

/**
 * BudgetEntryService facade
 *
 * @package App\Facades\Services
 *
 * @method static void setOrder(BudgetEntry $entry, int $order)
 * @method static void setStatus(BudgetEntry $entry, array $data)
 */
class BudgetEntryService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BudgetEntryService::class;
    }
}

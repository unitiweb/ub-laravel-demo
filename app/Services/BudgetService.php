<?php

namespace App\Services;

use App\Facades\Services\AuthService;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\BudgetIncome;
use App\Models\Group;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class to handle budget related tasks
 *
 * @package App\Services
 */
class BudgetService
{
    /**
     * Create a budget for the given month
     * The month and be a date string of any day of the month
     *
     * @param Carbon $month The month
     *
     * @return Budget
     */
    public function addBudget(Carbon $month): Budget
    {
        $siteId = AuthService::getSite()->id;

        // Create the budget and all its entries, incomes, and groups
        return DB::transaction(function () use ($siteId, $month) {
            // Create the budget
            $budget = Budget::create([
                'siteId' => AuthService::getSite()->id,
                'month' => $month,
            ]);

            // If there is a previous budget then duplicate it into the new budget
            $prevBudget = Budget::where('month', '<', $month->format('Y-m-d'))
                ->orderBy('month', 'desc')
                ->first();

            if ($prevBudget) {
                $this->duplicateBudget($siteId, $prevBudget, $budget);
            }

            return $budget;
        });
    }

    /**
     * Take a previous budget and copy it to another budget
     *
     * @param int $siteId
     * @param Budget $prevBudget
     * @param Budget $budget
     *
     * @return void
     */
    public function duplicateBudget(int $siteId, Budget $prevBudget, Budget $budget): void
    {
        $incomes = [];
        foreach ($prevBudget->incomes as $prevIncome) {
            array_push($incomes, [
                'siteId' => $siteId,
                'budgetId' => $budget->id,
                'name' => $prevIncome->name,
                'dueDay' => $prevIncome->dueDay,
                'amount' => $prevIncome->amount
            ]);
        }
        BudgetIncome::insert($incomes);

        $groups = [];
        foreach ($prevBudget->groups as $prevGroup) {
            array_push($groups, [
                'siteId' => $siteId,
                'budgetId' => $budget->id,
                'groupId' => $prevGroup->groupId,
                'name' => $prevGroup->name,
                'order' => $prevGroup->order,
            ]);
        }
        BudgetGroup::insert($groups);

        $lastDay = (new CarbonImmutable($prevBudget->month))->endOfMonth()->day;

        $entries = [];
        foreach ($prevBudget->entries as $prevEntry) {
            // Get the new income id for this budget
            $incomeId = null;
            if ($prevEntry->budgetIncomeId) {
                $prevIncome = BudgetIncome::where('id', $prevEntry->budgetIncomeId)->first();
                $newIncome = BudgetIncome::where('budgetId', $budget->id)->where('name', $prevIncome->name)->first();
                $incomeId = $newIncome->id;
            }

            $groupId = null;
            if ($prevEntry->budgetGroupId) {
                $prevGroup = BudgetGroup::where('id', $prevEntry->budgetGroupId)->first();
                $newGroup = BudgetGroup::where('budgetId', $budget->id)->where('name', $prevGroup->name)->first();
                $groupId = $newGroup->id;
            }

            // If due on the last day set dueDay to the last day of this budget's month
            if ($dueDay = $prevEntry->dueDay) {
                if ($dueDay > $lastDay) {
                    $dueDay = $lastDay;
                }
            }

            array_push($entries, [
                'siteId' => $siteId,
                'budgetId' => $budget->id,
                'budgetIncomeId' => $incomeId,
                'budgetGroupId' => $groupId,
                'categoryId' => $prevEntry->categoryId,
                'name' => $prevEntry->name,
                'dueDay' => $dueDay,
                'amount' => $prevEntry->amount,
                'autoPay' => $prevEntry->autoPay,
                'url' => $prevEntry->url,
                'goal' => false,
                'paid' => false,
                'cleared' => false,
            ]);
        }

        BudgetEntry::insert($entries);
    }

    /**
     * Get a list of budget groups and global groups as a sorted collection
     *
     * @param Budget $budget The budget to get budgetGroups for
     *
     * @return Collection
     */
    public function getGroupsSortedList(Budget $budget): Collection
    {
        // Get budget groups to be set as additional data
        $budgetGroups = BudgetGroup::select('id', 'groupId', 'name', 'order')
            ->where('budgetId', $budget->id)
            ->orderBy('order', 'asc')
            ->get();

        // Get global groups and filter out the budget groups
        $globalGroups = Group::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->get()
            ->filter(function ($group) use ($budgetGroups) {
                return !$budgetGroups->firstWhere('groupId', $group->id);
            });

        $groupsList = collect();
        $index = 1;
        foreach ($budgetGroups as $group) {
            $groupsList->push(collect([
                'id' => $group->id,
                'name' => $group->name,
                'order' => $group->order,
                'type' => 'budgetGroup',
            ]));
            $index++;
        }

        foreach ($globalGroups as $group) {
            $groupsList->push(collect([
                'id' => $group->id,
                'name' => $group->name,
                'order' => $group->order,
                'type' => 'group',
            ]));
        }

        return $groupsList->sortBy([
            ['order', 'asc'],
            ['name', 'asc'],
        ]);
    }
}

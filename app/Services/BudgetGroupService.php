<?php

namespace App\Services;

use App\Facades\Services\AuthService;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\Group;

/**
 * Service class for BudgetGroup tasks
 *
 * @package App\Services
 */
class BudgetGroupService
{
    /**
     * There may be budget groups that are not tied to an entry
     * These budget groups need to be removed
     * These groups will be removed
     *
     * @param Budget $budget
     *
     * @return void
     */
    public function cleanBudgetGroups(Budget $budget): void
    {
        $groups = BudgetGroup::where('budgetId', $budget->id)->get();
        foreach ($groups as $group) {
            if ($group->entries->count() === 0) {
                $group->delete();
            }
        }
    }

    /**
     * Take the given entry and global group id and create a budget group
     * and set the budgetGroupId on the budget entry
     *
     * @param Budget $budget
     * @param BudgetEntry $entry
     * @param int $groupId
     *
     * @return void
     */
    public function setBudgetGroup(Budget $budget, BudgetEntry $entry, int $groupId): void
    {
        $group = Group::where('id', $groupId)->firstOrFail();

        $budgetGroup = BudgetGroup::create([
            'siteId' => AuthService::getSite()->id,
            'budgetId' => $budget->id,
            'groupId' => $group->id,
            'name' => $group->name,
            'order' => $group->order,
        ]);

        $entry->budgetGroupId = $budgetGroup->id;
    }
}

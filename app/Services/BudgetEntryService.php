<?php

namespace App\Services;

use App\Models\BudgetEntry;

/**
 * Service class for BudgetEntry tasks
 *
 * @package App\Services
 */
class BudgetEntryService
{
    /**
     * Put the current entry in the correct given order
     *
     * @param BudgetEntry $entry
     * @param int $order
     *
     * @return void
     */
    public function setOrder(BudgetEntry $entry, int $order): void
    {
        if ($order <= 0) {
            $order = 1;
        }

        $entries = BudgetEntry::where('id', '!=', $entry->id)
            ->where('budgetGroupId', $entry->budgetGroupId)
            ->orderBy('order', 'asc')
            ->get();

        $i = 1;
        foreach ($entries as $entry) {
            if ($i === $order) {
                $i++;
            }
            $entry->update(['order' => $i]);
            $i++;
        }

        if ($order > $entries->count()) {
            $order = $entries->count();
        }

        $entry->order = $order;
    }

    /**
     * Update the entry status
     * Mark true all lower statuses
     * If Cleared is true then so is goal and paid, and all other scenarios
     *
     * @param BudgetEntry $entry
     * @param array $data A data array setting either goal, paid, or cleared
     *
     * @return void
     */
    public function setStatus(BudgetEntry $entry, array $data): void
    {
        if (isset($data['cleared'])) {
            $entry->goal = true;
            $entry->paid = true;
        } elseif (isset($data['paid'])) {
            $entry->goal = true;
            $entry->cleared = false;
        } elseif (isset($data['goal'])) {
            $entry->paid = false;
            $entry->cleared = false;
        }
    }
}

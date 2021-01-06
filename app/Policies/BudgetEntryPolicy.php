<?php

namespace App\Policies;

use App\Models\BudgetEntry;
use App\Models\User;

/**
 * Budget Entry policy to protect budget entries from unauthorized actions
 *
 * @package App\Policies
 */
class BudgetEntryPolicy
{
    /**
     * Determine if the given budget entry can be viewed by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetEntry $entry The budget in question
     *
     * @return bool
     */
    public function view(User $user, BudgetEntry $entry): bool
    {
        return $user->siteId === $entry->siteId;
    }

    /**
     * Determine if the given budget entry can be stored by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetEntry $entry The budget in question
     *
     * @return bool
     */
    public function store(User $user, BudgetEntry $entry): bool
    {
        return $user->siteId === $entry->siteId;
    }

    /**
     * Determine if the given budget entry can be updated by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetEntry $entry The budget in question
     *
     * @return bool
     */
    public function update(User $user, BudgetEntry $entry): bool
    {
        return $user->siteId === $entry->siteId;
    }

    /**
     * Determine if the given budget entry can be deleted by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetEntry $entry The budget in question
     *
     * @return bool
     */
    public function destroy(User $user, BudgetEntry $entry): bool
    {
        return $user->siteId === $entry->siteId;
    }
}

<?php

namespace App\Policies;

use App\Models\BudgetGroup;
use App\Models\User;

/**
 * Budget Entry policy to protect budget groups from unauthorized actions
 *
 * @package App\Policies
 */
class BudgetGroupPolicy
{
    /**
     * Determine if the given budget income can be viewed by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetGroup $group The budget in question
     *
     * @return bool
     */
    public function view(User $user, BudgetGroup $group): bool
    {
        return $user->siteId === $group->siteId;
    }

    /**
     * Determine if the given budget income can be stored by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetGroup $group The budget in question
     *
     * @return bool
     */
    public function store(User $user, BudgetGroup $group): bool
    {
        return $user->siteId === $group->siteId;
    }

    /**
     * Determine if the given budget income can be updated by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetGroup $group The budget in question
     *
     * @return bool
     */
    public function update(User $user, BudgetGroup $group): bool
    {
        return $user->siteId === $group->siteId;
    }

    /**
     * Determine if the given budget income can be deleted by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetGroup $group The budget in question
     *
     * @return bool
     */
    public function destroy(User $user, BudgetGroup $group): bool
    {
        return $user->siteId === $group->siteId;
    }
}

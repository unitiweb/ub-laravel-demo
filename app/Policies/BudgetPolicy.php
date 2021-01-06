<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;

/**
 * Budget policy to protect budgets from unauthorized actions
 *
 * @package App\Policies
 */
class BudgetPolicy
{
    /**
     * Determine if the given budget can be viewed by the user.
     *
     * @param User $user The authenticated user
     * @param Budget $budget The budget in question
     *
     * @return bool
     */
    public function view(User $user, Budget $budget): bool
    {
        return $user->siteId === $budget->siteId;
    }

    /**
     * Determine if the given budget can be stored by the user.
     *
     * @param User $user The authenticated user
     * @param Budget $budget The budget in question
     *
     * @return bool
     */
    public function store(User $user, Budget $budget): bool
    {
        return $user->siteId === $budget->siteId;
    }

    /**
     * Determine if the given budget can be updated by the user.
     *
     * @param User $user The authenticated user
     * @param Budget $budget The budget in question
     *
     * @return bool
     */
    public function update(User $user, Budget $budget): bool
    {
        return $user->siteId === $budget->siteId;
    }

    /**
     * Determine if the given budget can be deleted by the user.
     *
     * @param User $user The authenticated user
     * @param Budget $budget The budget in question
     *
     * @return bool
     */
    public function destroy(User $user, Budget $budget): bool
    {
        return $user->siteId === $budget->siteId;
    }
}

<?php

namespace App\Policies;

use App\Models\BudgetIncome;
use App\Models\User;

/**
 * Budget Entry policy to protect budget incomes from unauthorized actions
 *
 * @package App\Policies
 */
class BudgetIncomePolicy
{
    /**
     * Determine if the given budget income can be viewed by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetIncome $income The budget in question
     *
     * @return bool
     */
    public function view(User $user, BudgetIncome $income): bool
    {
        return $user->siteId === $income->siteId;
    }

    /**
     * Determine if the given budget income can be stored by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetIncome $income The budget in question
     *
     * @return bool
     */
    public function store(User $user, BudgetIncome $income): bool
    {
        return $user->siteId === $income->siteId;
    }

    /**
     * Determine if the given budget income can be updated by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetIncome $income The budget in question
     *
     * @return bool
     */
    public function update(User $user, BudgetIncome $income): bool
    {
        return $user->siteId === $income->siteId;
    }

    /**
     * Determine if the given budget income can be deleted by the user.
     *
     * @param User $user The authenticated user
     * @param BudgetIncome $income The budget in question
     *
     * @return bool
     */
    public function destroy(User $user, BudgetIncome $income): bool
    {
        return $user->siteId === $income->siteId;
    }
}

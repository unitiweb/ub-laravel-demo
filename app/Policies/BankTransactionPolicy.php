<?php

namespace App\Policies;

use App\Models\BankTransaction;
use App\Models\User;

/**
 * Budget Entry policy to protect budget entries from unauthorized actions
 *
 * @package App\Policies
 */
class BankTransactionPolicy
{
    /**
     * Determine if the given budget entry can be viewed by the user.
     *
     * @param User $user The authenticated user
     * @param BankTransaction $transaction The budget in question
     *
     * @return bool
     */
    public function view(User $user, BankTransaction $transaction): bool
    {
        return $user->siteId === $transaction->siteId;
    }

    /**
     * Determine if the given budget entry can be stored by the user.
     *
     * @param User $user The authenticated user
     * @param BankTransaction $transaction The budget in question
     *
     * @return bool
     */
    public function store(User $user, BankTransaction $transaction): bool
    {
        return $user->siteId === $transaction->siteId;
    }

    /**
     * Determine if the given budget entry can be updated by the user.
     *
     * @param User $user The authenticated user
     * @param BankTransaction $transaction The budget in question
     *
     * @return bool
     */
    public function update(User $user, BankTransaction $transaction): bool
    {
        return $user->siteId === $transaction->siteId;
    }

    /**
     * Determine if the given budget entry can be deleted by the user.
     *
     * @param User $user The authenticated user
     * @param BankTransaction $transaction The budget in question
     *
     * @return bool
     */
    public function destroy(User $user, BankTransaction $transaction): bool
    {
        return $user->siteId === $transaction->siteId;
    }
}

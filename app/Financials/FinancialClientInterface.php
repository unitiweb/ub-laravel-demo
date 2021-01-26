<?php

namespace App\Financials;

use App\Financials\Models\FinancialInstitution;
use App\Financials\Plaid\Webhooks\Syncs\AccountsSync;
use App\Models\BankAccessToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * Interface FinancialClientInterface
 *
 * @package App\Financials
 */
interface FinancialClientInterface
{
    /**
     * Create a link token to facilitate a bank account link
     *
     * @param int $siteId
     *
     * @return Collection
     */
    public function createLinkToken(int $siteId): Collection;

    /**
     * Exchange a public token to get an access token
     * The user gets a public token by completing the Plaid bank authentication form
     *
     * @param string $publicToken The public token from the auth form
     * @param int $siteId
     *
     * @return Collection
     */
    public function createAccessToken(string $publicToken, int $siteId): Collection;

    /**
     * Get accounts accounts from the Plaid service
     *
     * @param BankAccessToken $token
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return Collection
     */
    public function getTransactions(BankAccessToken $token, Carbon $startDate, Carbon $endDate): Collection;

    /**
     * Get a list of accounts and account balances
     *
     * @param BankAccessToken $token
     *
     * @return Collection
     */
    public function getAccounts(BankAccessToken $token): Collection;

    /**
     * Get the institution by name
     *
     * @param BankAccessToken $token
     * @param string $institutionId
     *
     * @return FinancialInstitution|null
     */
    public function getInstitution(BankAccessToken $token, string $institutionId): ?FinancialInstitution;
}

<?php

namespace App\Financials;

use App\Models\BankAccessToken;
use App\Models\BankLinkToken;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
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
     * @throws Exception
     * @throws GuzzleException
     */
    public function createAccessToken(string $publicToken, int $siteId): Collection;
}

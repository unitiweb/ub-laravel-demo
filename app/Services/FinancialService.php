<?php

namespace App\Services;

use App\Facades\Financial;
use App\Financials\Plaid\Plaid;
use App\Models\BankAccessToken;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

/**
 * Class FinancialService
 * @package App\Services
 */
class FinancialService
{
    /**
     * Get environment settings the front end will use to configure client
     *
     * @return array
     */
    public function getSettings(): array
    {
       return [
           'environment' => config('financial.plaid.environment'),
       ];
    }

    /**
     * Get the link token
     *
     * @param BankAccessToken $bankAuth
     *
     * @return array
     * @throws GuzzleException
     */
    public function createLinkToken(BankAccessToken $bankAuth): array
    {
        $result = (new Plaid($bankAuth))->createLinkToken();

        return [
            'expiration' => $result->getExpiration(),
            'linkToken' => $result->getLinkToken(),
            'requestId' => $result->getRequestId(),
        ];
    }

    /**
     * Take in the public token and create an access token
     *
     * @param string $publicToken
     *
     * @return array
     */
    public function exchangeLinkToken(string $publicToken): array
    {
        $result = (new Plaid($bankAuth))->createLinkToken();
        $result = Plaid::getAccessToken($publicToken);

        return [
            'itemId' => $result->getItemId(),
            'accessToken' => $result->getAccessToken(),
            'requestId' => $result->getRequestId(),
        ];
    }

    public function getAccounts(BankAccessToken $bankAuth): Collection
    {
        $result = Plaid::getBalances();

        $accounts = [];
        foreach ($result->getAccounts() as $account) {
            $balance = $account->getBalances();

            $accounts[] = collect([
                'accountId' => $account->getAccountId(),
                'balances' => [
                    'available' => $balance->getAvailable(),
                    'current' => $balance->getCurrent(),
                    'isoCurrencyCode' => $balance->getIsoCurrencyCode(),
                    'limit' => $balance->getLimit(),
                    'unofficialCurrencyCode' => $balance->getUnofficialCurrencyCode(),
                ],
                'mask' => $account->getMask(),
                'name' => $account->getName(),
                'officialName' => $account->getOfficialName(),
                'subType' => $account->getSubtype(),
                'type' => $account->getType(),
            ]);
        }

        return collect($accounts);
    }
}

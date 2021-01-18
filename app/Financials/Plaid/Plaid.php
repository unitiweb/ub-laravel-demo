<?php

namespace App\Financials\Plaid;

use App\Financials\FinancialClientInterface;
use App\Models\BankAccessToken;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class Plaid
 * @package App\Financials
 */
class Plaid implements FinancialClientInterface
{
    const DEFAULT_DAYS_BACK = 90;
    const MAX_RECORDS = 100;

    /**
     * The plaid client
     *
     * @var PlaidClient
     */
    private $client;

    /**
     * Plaid constructor.
     */
    public function __construct()
    {
        $this->client = new PlaidClient();
    }

    /**
     * Get a link token to be used in the UI to authenticated to banks and receive a public token
     * The public token is then used to get an access_token
     *
     * @param int $siteId
     *
     * @return Collection
     * @throws Exception
     */
    public function createLinkToken(int $siteId): Collection
    {
        $response = $this->client->request('link/token/create')
            ->clientDetails()
            ->body([
                'user' => [
                    'client_user_id' => 'unitibudget:'. $siteId,
                ],
                'client_name' => config('app.name'),
                'country_codes' => explode(',', config('financial.plaid.country_codes')),
                'language' => config('financial.plaid.language'),
                'products' => config('financial.plaid.products'),
                'webhook' => config('app.webhook_url').config('financial.plaid.webhook') . '/' . $siteId,
            ])
            ->send();

        return collect([
            'expiration' => new Carbon($response['expiration']),
            'token' => $response['link_token'],
            'requestId' => $response['request_id'],
            'environment' => config('financial.plaid.environment'),
        ]);
    }

    /**
     * Exchange a public token to get an access token
     * The user gets a public token by completing the Plaid bank authentication form
     *
     * @param string $publicToken The public token from the auth form
     * @param int $siteId
     *
     * @return Collection
     * @throws Exception|GuzzleException
     */
    public function createAccessToken(string $publicToken, int $siteId): Collection
    {
        $response = $this->client->request('item/public_token/exchange')
            ->body(['public_token' => $publicToken])
            ->send();

        return collect([
            'requestId' => $response['request_id'],
            'itemId' => $response['item_id'],
            'token' => $response['access_token'],
        ]);
    }

    /**
     * Get accounts from the Plaid service
     *
     * @param BankAccessToken $token
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @param array $options
     *
     * @return Collection
     */
    public function getTransactions(BankAccessToken $token, ?Carbon $startDate = null, ?Carbon $endDate = null, array $options = []): Collection
    {
        if ($startDate === null) {
            $startDate = (new Carbon)->days(-self::DEFAULT_DAYS_BACK);
        }

        if ($endDate === null) {
            $endDate = (new Carbon);
        }

        // Set the max records count if it's not already set
        $options['count'] = $options['count'] ?? self::MAX_RECORDS;

        $body = [
            'access_token' => $token->token,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'options' => $options,
        ];

        try {
            $results = $this->client
                ->request('transactions/get')
                ->body($body)
                ->send();
        } catch (Throwable $ex) {
            print_r($ex); exit;
        }


        return collect($results['transactions'] ?? []);
    }

    /**
     * @param BankAccessToken $token
     *
     * @param array|null $accountIds
     * @return Collection
     * @throws GuzzleException
     * @throws JsonError
     */
    public function getAccounts(BankAccessToken $token, ?array $accountIds = null): Collection
    {
        // Set the access token
        $body = ['access_token' => $token->token];

        // Filter accounts by ids if present
        if (is_array($accountIds) && count($accountIds) >= 1) {
            $body['options']['account_ids'] = $accountIds;
        }

        $results = $this->client
            ->request('accounts/get')
            ->body($body)
            ->send();

        $accounts = collect($results['accounts'] ?? []);
        $item = collect($results['item'] ?? []);

        return collect([
            'item' => $item,
            'accounts' => $accounts,
        ]);
    }


    /**
     * Get the institution by name
     * @param BankAccessToken $token
     * @param string $institutionId
     * @param array|null $options
     *
     * @return Collection
     * @throws GuzzleException
     * @throws JsonError
     */
    public function getInstitution(BankAccessToken $token, string $institutionId, array $options = null): Collection
    {
        // Set the access token
        $body = [
            'institution_id' => $institutionId,
            'country_codes' => explode(',', config('financial.plaid.country_codes')),
        ];

        if ($options !== null) {
            $body['options'] = $options;
        }

        $results = $this->client
            ->request('institutions/get_by_id')
            ->body($body)
            ->send();

        return collect($results['institution']);
    }
}

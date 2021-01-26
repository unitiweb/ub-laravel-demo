<?php

namespace App\Financials\Drivers\Plaid;

use App\Financials\FinancialClientInterface;
use App\Financials\Models\FinancialAccount;
use App\Financials\Models\FinancialAccountBalances;
use App\Financials\Models\FinancialInstitution;
use App\Financials\Models\FinancialTransaction;
use App\Models\BankAccessToken;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

/**
 * Class Plaid
 * @package App\Financials
 */
class Plaid implements FinancialClientInterface
{
    const MAX_RECORDS = 100;

    /**
     * The plaid client
     *
     * @var PlaidClient
     */
    private $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * Plaid constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new PlaidClient($config);
    }

    /**
     * Get a link token to be used in the UI to authenticated to banks and receive a public token
     * The public token is then used to get an access_token
     *
     * @param int $siteId
     *
     * @return Collection
     * @throws Exception
     * @throws GuzzleException
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
                'country_codes' => explode(',', $this->config['country_codes']),
                'language' => $this->config['language'],
                'products' => $this->config['products'],
                'webhook' => route('webhook.plaid', ['siteId' => $siteId]),
            ])
            ->send();

        return collect([
            'expiration' => new Carbon($response['expiration']),
            'token' => $response['link_token'],
            'requestId' => $response['request_id'],
            'environment' => $this->config['environment'],
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
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */
    public function getTransactions(BankAccessToken $token, Carbon $startDate, Carbon $endDate): Collection
    {
        // Set the max records count if it's not already set
        $options['count'] = $options['count'] ?? self::MAX_RECORDS;

        $body = [
            'access_token' => $token->token,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'options' => $options,
        ];

        $results = $this->client
            ->request('transactions/get')
            ->body($body)
            ->send();

        $transactions = collect();
        foreach ($results['transactions'] as $transaction) {
            $transactions->push(new FinancialTransaction([
                'accountId' => $transaction['account_id'],
                'transactionId' => $transaction['transaction_id'],
                'category' => $transaction['category'],
                'amount' => $transaction['amount'],
                'transactionDate' => $transaction['date'],
                'paymentChannel' => $transaction['payment_channel'],
                'isoCurrencyCode' => $transaction['iso_currency_code'],
                'name' => $transaction['name'],
                'pending' => $transaction['pending'],
            ]));
        }

        return $transactions;
    }

    public function refreshTransactions(BankAccessToken $token, ?Carbon $startDate = null, ?Carbon $endDate = null, array $options = []): Collection
    {
        $body = [
            'access_token' => $token->token,
        ];

        $results = $this->client
            ->request('transactions/get')
            ->body($body)
            ->send();

        return collect($results['transactions'] ?? []);
    }

    /**
     * @param BankAccessToken $token
     *
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */
    public function getAccounts(BankAccessToken $token): Collection
    {
        // Set the access token
        $body = ['access_token' => $token->token];

        $results = $this->client
            ->request('accounts/get')
            ->body($body)
            ->send();

        $institutionId = $results['item']['institution_id'] ?? null;

        $data = collect();
        foreach ($results['accounts'] ?? [] as $account) {
            $balances = $account['balances'];

            $data->push(new FinancialAccount([
                'accountId' => $account['account_id'],
                'institutionId' => $institutionId,
                'mask' => $account['mask'],
                'name' => $account['name'],
                'officialName' => $account['official_name'],
                'subType' => $account['subtype'],
                'type' => $account['type'],
                'balances' => new FinancialAccountBalances([
                    'available' => $balances['available'],
                    'current' => $balances['current'],
                    'limit' => $balances['limit'],
                    'isoCurrencyCode' => $balances['iso_currency_code'],
                    'unofficialCurrencyCode' => $balances['unofficial_currency_code'],
                ]),
            ]));
        }

        return $data;
    }

    /**
     * Get the institution by name
     * @param BankAccessToken $token
     * @param string $institutionId
     *
     * @return FinancialInstitution|null
     * @throws GuzzleException
     * @throws Exception
     */
    public function getInstitution(BankAccessToken $token, string $institutionId): ?FinancialInstitution
    {
        // Set the access token
        $body = [
            'institution_id' => $institutionId,
            'country_codes' => explode(',', $this->config['country_codes']),
            'options' => [
                'include_optional_metadata' => true,
            ]
        ];

        $results = $this->client
            ->request('institutions/get_by_id')
            ->body($body)
            ->send();

        if ($results['institution'] ?? null) {
            return new FinancialInstitution([
                'institutionId' => $results['institution']['institution_id'],
                'name' => $results['institution']['name'],
                'url' => $results['institution']['url'],
                'logo' => $results['institution']['logo'],
                'primaryColor' => $results['institution']['primary_color'],
            ]);
        }

        return null;
    }
}

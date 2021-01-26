<?php

namespace App\Financials;

use App\Financials\Models\FinancialInstitution;
use App\Financials\Sync\FinancialAccountSync;
use App\Financials\Sync\FinancialCategoriesSync;
use App\Financials\Sync\FinancialTransactionsSync;
use App\Financials\Traits\FinancialTraits;
use App\Models\BankAccessToken;
use App\Models\BankLinkToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * Class Financial
 * @package App\Financials
 */
class Financial
{
    use FinancialTraits;

    const DEFAULT_DAYS_BACK = 90;

    /**
     * @var FinancialClientInterface
     */
    protected $client;

    /**
     * The authenticated siteId
     *
     * @var int
     */
    protected $siteId;

    /**
     * Financial constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = $this->loadDriver();
    }

    /**
     * Get the link token
     *
     * @param int $siteId
     *
     * @return BankLinkToken
     */
    public function createLinkToken(int $siteId): BankLinkToken
    {
        $token = $this->client->createLinkToken($siteId);

        // Remove all expired link tokens
        BankLinkToken::where('expiration', '<', (new Carbon)->format('Y-m-d H:i:s'))->delete();

        return BankLinkToken::create([
            'siteId' => $siteId,
            'expiration' => $token->get('expiration'),
            'token' => $token->get('token'),
            'requestId' => $token->get('requestId'),
            'environment' => $token->get('environment'),
        ]);
    }

    /**
     * Take in the public token created by using the link token and create an access token
     * If an access token was returned with the same itemId then we need to just update what we already have
     *
     * @param string $publicToken
     * @param int $siteId
     *
     * @return BankAccessToken
     */
    public function createAccessToken(string $publicToken, int $siteId): BankAccessToken
    {
        $token = $this->client->createAccessToken($publicToken, $siteId);

        // Check if a token with that itemId already exists
        if (!$accessToken = BankAccessToken::where('itemId', $token->get('itemId'))->first()) {
            // Create the access Token
            $accessToken =  new BankAccessToken;
            $accessToken->siteId = $siteId;
            $accessToken->itemId = $token->get('itemId');
        }

        // Update or add values to the access token
        $accessToken->requestId = $token->get('requestId');
        $accessToken->token = $token->get('token');
        $accessToken->save();

        return $accessToken;
    }

    /**
     * Get all bank accounts
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return Collection
     */
    public function getAccounts(BankAccessToken $bankAccessToken): Collection
    {
        return $this->client->getAccounts($bankAccessToken);
    }

    /**
     * Get all transactions from the client
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

        return $this->client->getTransactions($token, $startDate, $endDate);
    }

    /**
     * Get a list of institutions
     *
     * @param BankAccessToken $bankAccessToken
     * @param string $institutionId
     *
     * @return FinancialInstitution|null
     */
    public function getInstitution(BankAccessToken $bankAccessToken, string $institutionId): ?FinancialInstitution
    {
        return $this->client->getInstitution($bankAccessToken, $institutionId);
    }

    /**
     * Sync all accounts
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function accountsSync(BankAccessToken $bankAccessToken): bool
    {
        $sync = App::make(FinancialAccountSync::class);
        return $sync->sync($bankAccessToken);
    }

    /**
     * Sync all transactions
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function transactionsSync(BankAccessToken $bankAccessToken): bool
    {
        $sync = App::make(FinancialTransactionsSync::class);
        return $sync->sync($bankAccessToken);
    }

    /**
     * Sync all categories
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function categoriesSync(BankAccessToken $bankAccessToken): bool
    {
        $sync = App::make(FinancialCategoriesSync::class);
        return $sync->sync($bankAccessToken);
    }
}

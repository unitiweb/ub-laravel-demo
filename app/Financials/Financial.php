<?php

namespace App\Financials;

use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankLinkToken;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Financial
 * @package App\Financials
 */
class Financial
{
    /**
     * @var FinancialClientInterface
     */
    protected $client;

    public function __construct()
    {
        $currentDriver = config('financial.driver');

        $this->client = new $currentDriver();
        assert($this->client instanceof FinancialClientInterface);
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
     * @throws GuzzleException
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
     * @param array|null $accountIds
     *
     * @return Collection
     */
    public function getAccounts(array $accountIds = null): Collection
    {
        $query = BankAccount::siteOnly()->inclueWith();

        if ($accountIds !== null) {
            $query->whereIn('accountId', $accountIds);
        }

        return $query->get();
    }
}

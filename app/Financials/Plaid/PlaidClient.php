<?php

namespace App\Financials\Plaid;

use GuzzleHttp\Client;

/**
 * Class PlaidClient
 * @package App\Financials\Plaid
 */
class PlaidClient
{
    /**
     * The http client
     *
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * Plaid constructor.
     */
    public function __construct()
    {
        $this->clientId = config('financial.plaid.credentials.client_id');
        $this->secret = config('financial.plaid.credentials.secret');
        $environment = config('financial.plaid.environment');
        $version = config('financial.plaid.version');

        $this->client = new Client([
            'base_uri' => "https://$environment.plaid.com",
            'timeout' => 2.0,
            'debug' => false,
            'headers' => [
                'Accept' => '*/*',
                'Content-Type' => 'application/json',
                "Plaid-Version" => $version,
            ],
        ]);
    }

    /**
     * Get a new plaid request instance
     *
     * @param string $uri The request uri
     *
     * @return PlaidRequest
     */
    public function request(string $uri): PlaidRequest
    {
        return new PlaidRequest($this->client, $this->clientId, $this->secret, $uri);
    }
}

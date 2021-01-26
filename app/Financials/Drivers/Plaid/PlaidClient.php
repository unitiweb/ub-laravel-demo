<?php

namespace App\Financials\Drivers\Plaid;

use GuzzleHttp\Client;

/**
 * Class PlaidClient
 * @package App\Financials\Drivers\Plaid
 */
class PlaidClient
{
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
        $environment = $this->config['environment'];
        $version = $this->config['version'];

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
        return new PlaidRequest($this->config, $this->client, $uri);
    }
}

<?php

namespace App\Financials\Plaid;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PlaidService
 * @package App\Financials\Plaid
 */
class PlaidRequest
{
    /**
     * Get the plaid guzzle client
     *
     * @var Client
     */
    protected $client;

    /**
     * The uri for the request
     *
     * @var string
     */
    protected $uri;

    /**
     * The Plaid client id
     *
     * @var string
     */
    protected $clientId;

    /**
     * The Plaid secret
     *
     * @var string
     */
    protected $secret;

    /**
     * The Plaid environment
     *
     * @var string
     */
    protected $environment;

    /**
     * The Plaid api version
     *
     * @var string
     */
    protected $version;

    /**
     * The user's access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The country codes for the plaid client
     * @var array
     */
    protected $countryCodes;

    /**
     * The name of the plaid client
     *
     * @var string
     */
    protected $clientName;

    /**
     * The language for the plaid client
     *
     * @var string
     */
    protected $language;

    /**
     * The request body
     *
     * @var array
     */
    protected $requestBody = [];

    /**
     * PlaidRequest constructor.
     *
     * @param Client $client
     * @param string $clientId
     * @param string $secret
     * @param string $uri
     */
    public function __construct(Client $client, string $clientId, string $secret, string $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
        $this->clientId = $clientId;
        $this->secret = $secret;

        $this->countryCodes = [
            config('financial.plaid.country')
        ];

        $this->clientName = config('app.name');
        $this->language = config('financial.plaid.language');

        // Add the credentials on all requests
        $this->credentials();
    }

    /**
     * Set the access token for the bank account
     *
     * @param string $token
     */
    public function setAccessToken(string $token)
    {
        $this->accessToken = $token;
    }

    /**
     * Add the credentials to the request
     *
     * @return self
     */
    public function credentials(): self
    {
        $this->requestBody = array_merge($this->requestBody, [
            'client_id' => $this->clientId,
            'secret' => $this->secret,
        ]);

        return $this;
    }

    /**
     * Add the access token to the request
     *
     * @return self
     */
    public function accessToken(): self
    {
        $this->requestBody = array_merge($this->requestBody, [
            'access_token' => $this->accessToken,
        ]);

        return $this;
    }

    /**
     * Add the client details to the request
     *
     * @return self
     */
    public function clientDetails(): self
    {
        $this->requestBody = array_merge($this->requestBody, [
            'client_name' => $this->clientName,
            'country_codes' => $this->countryCodes,
            'language' => $this->language,
        ]);

        return $this;
    }

    /**
     * Add options to the request
     *
     * @param array $options
     *
     * @return self
     */
    public function body(array $options): self
    {
        $this->requestBody = array_merge($this->requestBody, $options);

        return $this;
    }

    /**
     * Add options to the request
     *
     * @param array $options
     *
     * @return self
     */
    public function options(array $options): self
    {
        if (!isset($this->requestBody['options'])) {
            $this->requestBody['options'] = [];
        }

        $this->requestBody['options'] = array_merge($this->requestBody['options'], $options);

        return $this;
    }

    /**
     * Make a question to Plaid
     *
     * @param string $method The request method
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonError
     */
    public function send(string $method = 'POST'): array
    {
        // Add the credentials to all requests
        $this->credentials();

        try {
            $response = $this->client->request($method, $this->uri, [
                'json' => $this->requestBody,
            ]);
            return $this->response($response);
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            $error = json_decode((string) $response->getbody(), true);
            throw new Exception($error['error_message']);
//            throw new JsonError(
//                $error['error_message'] ?? null,
//                $ex->getcode(),
//                $error,
//                $error['error_code'] ?? null,
//                $ex
//            );
        }
    }

    /**
     * Process the response
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function response(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        if ($status === 200) {
            return json_decode($response->getBody(), true);
        } else if ($status === 400) {
            dd('404 not found');
        }

        return [];
    }
}

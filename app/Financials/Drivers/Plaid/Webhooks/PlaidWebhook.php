<?php

namespace App\Financials\Drivers\Plaid\Webhooks;

use App\Financials\Drivers\Plaid\Webhooks\Processors\TransactionProcessor;
use App\Financials\Drivers\Plaid\Webhooks\Processors\WebhookProcessorInterface;
use App\Financials\Financial;
use App\Financials\FinancialWebhookAbstract;
use App\Models\BankAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * Process Plaid webhooks
 *
 * @package App\Financials\Drivers\Plaid
 */
class PlaidWebhook extends FinancialWebhookAbstract
{
    const TRANSACTIONS = 'TRANSACTIONS';

    /**
     * Process the incoming webhook
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function process(Request $request): Response
    {
        // Validate the webhook request
        if (!$this->validate($request)) {
            abort(401, 'Webhook validation failed');
        }

        // Get request data
        $data = $request->all();

        // Check the data for errors
        $this->errorCheck($data);

        // Get the access token related to this request
        $token = $this->getAccessToken($data);

        // Set transactions webhook processor
        $webhook = null;
        if ($this->checkType($data, self::TRANSACTIONS)) {
            $webhook = new TransactionProcessor($token);
        }

        // Process the webhook
        if ($webhook) {
            assert($webhook instanceof WebhookProcessorInterface);
            $webhook->process($data);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the access token by itemId
     *
     * @param array $data The webhook request data array
     *
     * @return BankAccessToken
     * @throws Exception
     */
    protected function getAccessToken(array $data): BankAccessToken
    {
        if ($itemId = $data['item_id'] ?? null) {
            if ($token = BankAccessToken::where('itemId', $itemId)->first()) {
                return $token;
            }
        }

        $data['error_message'] = 'No available access token';
        Log::critical('Plaid: No available access token', $data);
        throw new Exception($data['error_message']);
    }

    /**
     * Validate the webhook requests
     *
     * @param Request $request
     *
     * @return bool
     * @throws Exception
     */
    public function validate(Request $request): bool
    {
        if ($jwt = $request->header('plaid-verification')) {
            list($header, $payload, $signature) = explode (".", $jwt);
            $decode = base64_decode($payload);
            $decode = json_decode($decode, true);
            $code = $decode['request_body_sha256'];
            if (hash('sha256', $request->getContent()) === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check the webhook type
     *
     * @param array $data The request data array
     * @param string $type The type to check for
     *
     * @return bool
     */
    protected function checkType(array $data, string $type): bool
    {
        return isset($data['webhook_type']) && $data['webhook_type'] === $type;
    }

    /**
     * Process a webhook error
     *
     * @param array $data
     *
     * @return void
     * @throws Exception
     */
    protected function errorCheck(array $data): void
    {
        if (!empty($data['error'])) {
            Log::error('Webhook Error', $data['error']);
            throw new Exception($data['error']);
        }
    }
}

<?php

namespace App\Financials\Drivers\Plaid\Webhooks;

use App\Models\BankAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Process Plaid webhooks
 *
 * @package App\Financials\Drivers\Plaid
 */
class PlaidWebhook
{
    const TYPE_TRANSACTIONS = 'TRANSACTIONS';

    /**
     * Validate the webhook requests
     *
     * @param Request $request
     *
     * @return bool
     * @throws Exception
     */
    public static function validate(Request $request): bool
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
     * Get the access token by itemId
     *
     * @param array $data The webhook request data array
     *
     * @return BankAccessToken
     * @throws Exception
     */
    public static function getAccessToken(array $data): BankAccessToken
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
     * Check the webhook type
     *
     * @param array $data The request data array
     *
     * @return string|null
     */
    public static function getHookType(array $data): ?string
    {
        return $data['webhook_type'] ?? null;
    }

    /**
     * Process a webhook error
     *
     * @param array $data
     *
     * @return string|null
     */
    public static function errorCheck(array $data): ?string
    {
        if (!empty($data['error'])) {
            Log::error('Webhook Error', $data['error']);
            return $data['error'];
        }

        return null;
    }
}

<?php

namespace App\Financials\Plaid\Webhooks\Processors;

interface WebhookProcessorInterface
{
    /**
     * Process the webhook
     *
     * @param array $data
     *
     * @return void
     */
    public function process(array $data): void;
}

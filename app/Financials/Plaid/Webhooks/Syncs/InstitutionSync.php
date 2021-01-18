<?php

namespace App\Financials\Plaid\Webhooks\Syncs;

use App\Exceptions\JsonError;
use App\Models\BankAccount;
use App\Models\BankBalance;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;

/**
 * Class AccountsSync
 *
 * @package App\Financials\Plaid\Webhooks\Sync
 */
class InstitutionSync extends Sync
{
    /**
     * @return bool
     * @throws GuzzleException
     * @throws JsonError
     */
    public function sync(): bool
    {
        // Get bank accounts
        $bankAccounts = BankAccount::where('siteId', $this->token->siteId)->get();
        $accounts = $this->plaid->getAccounts($this->token);

        // Map the accounts to the $this->accounts variable
        $accounts = $this->mapAccounts($accounts);

        $this->upsertAccounts($accounts, $bankAccounts);

        return true;
    }

    /**
     * @param Collection $accounts
     *
     * @return Collection
     */
    protected function mapAccounts(Collection $accounts): Collection
    {
        return $accounts->map(function ($item) {
            return collect([
                'accountId' => $item['account_id'] ?? null,
                'balances' => collect([
                    'available' => $item['balances']['available'] ?? null,
                    'current' => $item['balances']['current'] ?? null,
                    'isoCurrencyCode' => $item['balances']['iso_currency_code'] ?? null,
                    'limit' => $item['balances']['limit'] ?? null,
                    'unofficialCurrencyCode' => $item['balances']['unofficial_currency_code'] ?? null,
                ]),
                'mask' => $item['mask'] ?? null,
                'name' => $item['name'] ?? null,
                'officialName' => $item['official_name'] ?? null,
                'subType' => $item['subtype'] ?? null,
                'type' => $item['type'] ?? null,
            ]);
        });
    }

    /**
     * @param Collection $accounts
     * @param DbCollection $bankAccounts
     *
     * @return void
     */
    protected function upsertAccounts(Collection $accounts, DbCollection $bankAccounts): void
    {
        foreach ($accounts as $account) {
            if (!$bankAccount = $this->bankAccountExists($account->get('accountId'), $bankAccounts)) {
                // Create since the bank account doesn't exist
                $bankAccount = new BankAccount;
                $bankAccount->siteId = $this->token->siteId;
                $bankAccount->bankAccessTokenId = $this->token->id;
                $bankAccount->accountId = $account->get('accountId');
                $bankAccount->mask = $account->get('mask');
            }

            // Update the fields that may have changed or need added
            $bankAccount->name = $account->get('name');
            $bankAccount->officialName = $account->get('officialName');
            $bankAccount->subType = $account->get('subType');
            $bankAccount->type = $account->get('type');
            $bankAccount->save();

            // Get the balances off of the Plaid account
            $balances = $account->get('balances');

            if (!$bankBalance = $this->bankBalanceExists($bankAccount)) {
                // Create the bank balances since it doesn't exist yet
                $bankBalance = new BankBalance;
                $bankBalance->siteId = $this->token->siteId;
                $bankBalance->bankAccountId = $bankAccount->id;
            }

            // Update the fields that my have changed or need added
            $bankBalance->available = $balances->get('available');
            $bankBalance->current = $balances->get('current');
            $bankBalance->limit = $balances->get('limit');
            $bankBalance->isoCurrencyCode = $balances->get('isoCurrencyCode');
            $bankBalance->unofficialCurrencyCode = $balances->get('unofficialCurrencyCode');
            $bankBalance->save();
        }
    }
}

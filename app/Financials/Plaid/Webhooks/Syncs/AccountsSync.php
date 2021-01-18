<?php

namespace App\Financials\Plaid\Webhooks\Syncs;

use App\Exceptions\JsonError;
use App\Models\BankAccount;
use App\Models\BankBalance;
use App\Models\BankInstitution;
use App\Models\BankInstitutionAccount;
use App\Models\BankInstitutionDetail;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class AccountsSync
 *
 * @package App\Financials\Plaid\Webhooks\Sync
 */
class AccountsSync extends Sync
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
        $results = $this->plaid->getAccounts($this->token);

        $accounts = $results->get('accounts');
        $item = $results->get('item');

        // Upsert the institution
        $institutionDetail = $this->upsertInstitutionDetail($item);

        // Map the accounts to the $this->accounts variable
        $accounts = $this->mapAccounts($accounts, $institutionDetail);

        //Upsert the institution
        $institution = $this->upsertInstitution($institutionDetail);

        // Upsert all the accounts
        $this->upsertAccounts($accounts, $bankAccounts, $institution);

        return true;
    }

    /**
     * Map the accounts to a specific format
     *
     * @param Collection $accounts
     * @param BankInstitutionDetail|null $institutionDetail
     *
     * @return Collection
     */
    protected function mapAccounts(Collection $accounts, ?BankInstitutionDetail $institutionDetail): Collection
    {
        return $accounts->map(function ($item) use ($institutionDetail) {
            return collect([
                'institutionId' => $institutionDetail->institutionId ?? null,
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
     * @param BankInstitution|null $institution
     *
     * @return void
     */
    protected function upsertAccounts(Collection $accounts, DbCollection $bankAccounts, ?BankInstitution $bankInstitution): void
    {
        foreach ($accounts as $account) {
            if (!$bankAccount = $this->bankAccountExists($account->get('accountId'), $bankAccounts)) {
                // Create since the bank account doesn't exist
                $bankAccount = new BankAccount;
                $bankAccount->siteId = $this->token->siteId;
                $bankAccount->bankAccessTokenId = $this->token->id;
                $bankAccount->bankInstitutionId = $bankInstitution->id;
                $bankAccount->accountId = $account->get('accountId');
                $bankAccount->mask = $account->get('mask');
                $bankAccount->save();
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

    /**
     * Upsert the institution
     *
     * @param BankInstitutionDetail|null $institutionDetail
     *
     * @return BankInstitution
     */
    protected function upsertInstitution(?BankInstitutionDetail $institutionDetail): BankInstitution
    {
        $bankInstitution = BankInstitution::where('siteId', $this->token->siteId)
            ->where('bankInstitutionDetailId', $institutionDetail->id)
            ->first();

        if (!$bankInstitution) {
            $bankInstitution = new BankInstitution;
            $bankInstitution->siteId = $this->token->siteId;
            $bankInstitution->bankInstitutionDetailId = $institutionDetail->id;
            $bankInstitution->save();
        }

        return $bankInstitution;
    }

    /**
     * Upsert the institution information
     * @param Collection $item
     *
     * @return BankInstitutionDetail
     * @throws GuzzleException
     * @throws JsonError
     */
    protected function upsertInstitutionDetail(Collection $item): ?BankInstitutionDetail
    {
        // If the institution is already in the database then stop upsert
        if ($institutionDetail = BankInstitutionDetail::where('institutionId', $item['institution_id'])->first()) {
            return $institutionDetail;
        }

        $institution = $this->plaid->getInstitution(
            $this->token,
            $item['institution_id'],
            [
                'include_optional_metadata' => true,
            ]
        );

        // If the remote institution was not found then stop upsert
        if (!$institution) {
            return null;
        }

        // All's good and ready for upsert
        return BankInstitutionDetail::create([
            'institutionId' => $institution['institution_id'],
            'name' => $institution['name'],
            'url' => $institution['url'] ?? null,
            'logo' => $institution['logo'] ?? null,
            'primaryColor' => $institution['primary_color'] ?? null,
        ]);
    }

    /**
     * Get the bank account if it exists
     *
     * @param string $accountId
     * @param DbCollection $bankAccounts
     *
     * @return BankAccount|null
     */
    protected function bankAccountExists(string $accountId, DbCollection $bankAccounts): ?BankAccount
    {
        return $bankAccounts->where('accountId', $accountId)->first();
    }

    /**
     * Get the bank account's balances if they exist
     *
     * @param BankAccount $bankAccount
     *
     * @return BankBalance|null
     */
    protected function bankBalanceExists(BankAccount $bankAccount): ?BankBalance
    {
        return BankBalance::where('siteId', $this->token->siteId)
            ->where('bankAccountId', $bankAccount->id)
            ->first();
    }
}

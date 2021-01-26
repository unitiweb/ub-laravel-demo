<?php

namespace App\Financials\Sync;

use App\Financials\Models\FinancialAccount;
use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankBalance;
use App\Models\BankInstitution;
use App\Models\BankInstitutionDetail;

/**
 * Sync the accounts
 *
 * @package App\Financials
 */
class FinancialAccountSync extends FinancialSyncAbstract implements FinancialSyncInterface
{
    /**
     * @var BankAccessToken
     */
    protected $bankAccessToken;

    /**
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function sync(BankAccessToken $bankAccessToken): bool
    {
        $this->bankAccessToken = $bankAccessToken;
        // Get financial accounts
        $accounts = $this->financial->getAccounts($bankAccessToken);

        // Get bank accounts
        $bankAccounts = BankAccount::where('siteId', $bankAccessToken->siteId)->get();

        // Iterate through the financial accounts to sync with bank account records
        $accounts->each(function ($financialAccount) use ($bankAccounts) {
            $bankAccount = $bankAccounts->where('accountId', $financialAccount->accountId)->first();
            $bankAccount = $this->upsertBankAccount($bankAccount, $financialAccount);
            $this->upsertBankBalances($bankAccount, $financialAccount);
        });

        return true;
    }

    /**
     * Sync the bank account with the financial account details
     *
     * @param BankAccount|null $bankAccount
     * @param FinancialAccount $financialAccount
     *
     * @return BankAccount
     */
    protected function upsertBankAccount(?BankAccount $bankAccount, FinancialAccount $financialAccount): BankAccount
    {
        // Don't update the bank account if it exists and enabled is false
        if ($bankAccount && $bankAccount->enabled === false) {
            return $bankAccount;
        }

        // If the bank account doesn't exist then create one with settings and defaults
        if (!$bankAccount) {
            $bankAccount = new BankAccount;
            // Fields for creating the bank account
            $bankAccount->siteId = $this->bankAccessToken->siteId;
            $bankAccount->bankAccessTokenId = $this->bankAccessToken->id;
            $bankAccount->bankInstitutionId = $this->getInstitutionId($financialAccount);
            $bankAccount->accountId = $financialAccount->accountId;
            $bankAccount->enabled = false;
        }

        // Fields for updating the bank account
        $financialAccount->update($bankAccount);

        return $bankAccount;
    }

    /**
     * Get the bank accounts institution and create the institution if it doesn't exist
     *
     * @param FinancialAccount $financialAccount
     *
     * @return int
     */
    protected function getInstitutionId(FinancialAccount $financialAccount): int
    {
        // If there is no bank institution details then create it
        if (!$bankInstitutionDetail = BankInstitutionDetail::where('institutionId', $financialAccount->institutionId)->first()) {
            $institution = $this->financial->getInstitution($this->bankAccessToken, 'ins_24');
            $bankInstitutionDetail = BankInstitutionDetail::create([
               'institutionId' => $institution->institutionId,
                'name' => $institution->name,
                'url' => $institution->url,
                'logo' => $institution->logo,
                'primaryColor' => $institution->primaryColor,
            ]);
        }

        // Get the bank institution if it exists
        $bankInstitution = BankInstitution::where('siteId', $this->bankAccessToken->siteId)
            ->where('bankInstitutionDetailId', $bankInstitutionDetail->id)
            ->first();

        // If the bank institution doesn't exist then create it
        if (!$bankInstitution) {
            $bankInstitution = BankInstitution::create([
                'siteId' => $this->bankAccessToken->siteId,
                'bankInstitutionDetailId' => $bankInstitutionDetail->id
            ]);
        }

        return $bankInstitution->id;
    }

    /**
     * Store or update the bank balances
     *
     * @param BankAccount $bankAccount
     * @param FinancialAccount $financialAccount
     *
     * @return void
     */
    protected function upsertBankBalances(BankAccount $bankAccount, FinancialAccount $financialAccount): void
    {
        if (!$balances = BankBalance::where('bankAccountId', $bankAccount->id)->first()) {
            $balances = BankBalance::create([
                'siteId' => $this->bankAccessToken->siteId,
                'bankAccountId' => $bankAccount->id,
                'isoCurrencyCode' => $financialAccount->balances->isoCurrencyCode,
                'unofficialCurrencyCode' => $financialAccount->balances->unofficialCurrencyCode,
            ]);
        }

        // Update the bank balances
        $financialAccount->balances->update($balances);
    }
}

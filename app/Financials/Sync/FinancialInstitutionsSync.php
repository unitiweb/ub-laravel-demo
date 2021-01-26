<?php

namespace App\Financials\Sync;

use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankInstitution;
use App\Models\BankInstitutionDetail;

/**
 * Sync a user's account institutions
 *
 * @package App\Financials
 */
class FinancialInstitutionsSync extends FinancialSyncAbstract implements FinancialSyncInterface
{
    /**
     * Sync a user's institutions data
     *
     * @param BankAccessToken $bankAccessToken
     *
     * @return bool
     */
    public function sync(BankAccessToken $bankAccessToken): bool
    {
        $this->bankAccessToken = $bankAccessToken;

        $institutionIds = BankAccount::select('bankInstitutionId')
            ->where('bankAccessTokenId', $bankAccessToken->id)
            ->where('enabled', true)
            ->distinct()
            ->get()
            ->pluck('bankInstitutionId');

        foreach ($institutionIds as $id) {
            $this->update($id);
        }

        return true;
    }

    /**
     * Update the institution details if it exists
     *
     * @param int $id
     *
     * @return void
     */
    protected function update(int $id): void
    {
        if ($bankInstitution = BankInstitution::where('id', $id)->firstOrFail()) {
            $bankInstitutionDetails = BankInstitutionDetail::where('id', $bankInstitution->bankInstitutionDetailId)->firstOrFail();
            if ($institution = $this->financial->getInstitution($this->bankAccessToken, $bankInstitutionDetails->institutionId)) {
                $bankInstitutionDetails->name = $institution->name;
                $bankInstitutionDetails->url = $institution->url;
                $bankInstitutionDetails->logo = $institution->logo;
                $bankInstitutionDetails->primaryColor = $institution->primaryColor;
                $bankInstitutionDetails->save();
            }
        }
    }
}

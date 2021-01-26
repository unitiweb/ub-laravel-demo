<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Financial\BankAccountResource;
use App\Jobs\FinancialSyncJob;
use App\Models\BankAccount;
use App\Models\BankInstitution;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

/**
 * Class AccountController
 * @package App\Http\Controllers\Financial
 */
class AccountController extends ApiController
{
    /**
     * @var BankAccount
     */
    protected $bankAccount;

    /**
     * Get all the financial accounts
     *
     * @param BankInstitution $bankInstitution
     *
     * @return AnonymousResourceCollection
     */
    public function index(BankInstitution $bankInstitution): AnonymousResourceCollection
    {
        $with = $this->getWith(['institution', 'transactions']);

        $accounts = BankAccount::with($with->toArray())
            ->where('bankInstitutionId', $bankInstitution->id)
            ->get();

        return BankAccountResource::collection($accounts);
    }

    /**
     * Get a single bank account
     *
     * @param BankInstitution $bankInstitution
     * @param string $accountId
     *
     * @return BankAccountResource
     */
    public function show(BankInstitution $bankInstitution, string $accountId): BankAccountResource
    {
        $with = $this->getWith(['institution', 'transactions']);

        $bankAccount = BankAccount::with($with->toArray())
            ->where('bankInstitutionId', $bankInstitution->id)
            ->where('id', $accountId)
            ->first();

        return new BankAccountResource($bankAccount);
    }

    /**
     * Enable or disable this bank account
     *
     * @param Request $request
     * @param BankInstitution $bankInstitution
     * @param string $accountId
     *
     * @return BankAccountResource
     * @throws ValidationException
     */
    public function update(Request $request, BankInstitution $bankInstitution, string $accountId): BankAccountResource
    {
        $data = $this->validate($request, [
            'enabled' => 'sometimes|boolean'
        ]);

        $bankAccount = BankAccount::where('bankInstitutionId', $bankInstitution->id)
            ->where('id', $accountId)
            ->first();

        if (isset($data['enabled'])) {
            $bankAccount->enabled = $data['enabled'];
            $bankAccount->save();

            // If the account has been enabled then send a queue to sync data
            if ($bankAccount->enabled === true) {
                // Dispatch a queue job to update all transactions for the bank account
                FinancialSyncJob::dispatch($bankAccount->bankAccessToken);
            }
        }

        return new BankAccountResource($bankAccount);
    }
}

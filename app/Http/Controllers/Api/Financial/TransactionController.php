<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Financial\BankTransactionsGetRequest;
use App\Http\Resources\Financial\BankTransactionResource;
use App\Jobs\FinancialSyncJob;
use App\Models\BankAccessToken;
use App\Models\BankAccount;
use App\Models\BankInstitution;
use App\Models\BankTransaction;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Financial
 */
class TransactionController extends ApiController
{
    /**
     *
     * @param Request $request
     * @param BankInstitution $bankInstitution
     * @param BankAccount $bankAccount
     *
     * @return AnonymousResourceCollection
     * @throws ValidationException
     */
    public function index(Request $request, BankInstitution $bankInstitution, BankAccount $bankAccount): AnonymousResourceCollection
    {
        $data = $this->validate($request, [
            'startDate' => 'date_format:Y-m-d',
            'endDate' => 'date_format:Y-m-d',
            'filter' => 'string|nullable'
        ]);
        $filter = $data['filter'] ?? null;
        $startDate = $data['startDate'] ?? null;
        $endDate = $data['endDate'] ?? null;

        // Set the start and end dates as Carbon instances if they exist

        $with = $this->getWith(['account', 'entries', 'entries.budget', 'income', 'income.budget']);
        $query = BankTransaction::with($with->toArray());

        if ($startDate) {
            $startDate = isset($data['startDate']) ? new Carbon($data['startDate']) : new Carbon;
            $query->where('transactionDate', '>=', $startDate);
        }

        if ($endDate) {
            $endDate = isset($data['startDate']) ? new Carbon($data['startDate']) : new Carbon;
            $query->where('transactionDate', '<=', $endDate);
        }

        if ($filter) {
            $query->where(function ($query) use ($filter) {
                $query->orWhere('name', 'like', "%$filter%")
                    ->orWhere('amount', 'like', "%$filter%")
                    ->orWhere('category', 'like', "%$filter%");
            });
        }

        $transactions = $query->where('bankAccountId', $bankAccount->id)
            ->orderBy('transactionDate', 'desc')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();

        if (isset($data['filter'])) {
            $transactions->filter(function ($transaction) {

            });
        }

        return BankTransactionResource::collection($transactions);
    }

    /**
     * Get a single bank transaction
     *
     * @param BankInstitution $bankInstitution
     * @param BankAccount $bankAccount
     * @param BankTransaction $bankTransaction
     *
     * @return BankTransactionResource
     */
    public function show(BankInstitution $bankInstitution, BankAccount $bankAccount, BankTransaction $bankTransaction)
    {
        $with = $this->getWith(['account', 'entries', 'entries.budget']);
        $bankTransaction->load($with->toArray());

        return new BankTransactionResource($bankTransaction);
    }

//    public function store(BankInstitution $bankInstitution, BankAccount $bankAccount)
//    {
//
//        FinancialSyncJob::dispatch($bankAccount->bankAccessToken);
//
//        return response()->noContent();
//    }
}

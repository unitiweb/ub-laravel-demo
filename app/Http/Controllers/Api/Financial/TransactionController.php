<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\ApiController;
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
        ]);

        // Set the start and end dates as Carbon instances if they exist
        $startDate = isset($data['startDate']) ? new Carbon($data['startDate']) : new Carbon;
        $endDate = isset($data['endDate']) ? new Carbon($data['endDate']) : (new Carbon)->subDays(45);

        $transactions = BankTransaction::where('bankAccountId', $bankAccount->id)
            ->whereBetween('transactionDate', [$endDate, $startDate])
            ->orderBy('transactionDate', 'desc')
            ->orderBy('id', 'desc')
            ->limit(200)
            ->get();

        return BankTransactionResource::collection($transactions);
    }

    public function show()
    {
//        return new BankTransactionResource($this->transaction);
    }

    public function store(BankInstitution $bankInstitution, BankAccount $bankAccount)
    {

        FinancialSyncJob::dispatch($bankAccount->bankAccessToken);

        return response()->noContent();
    }
}

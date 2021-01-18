<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Financial\BankTransactionResource;
use App\Models\BankAccount;
use App\Models\BankInstitution;
use App\Models\BankTransaction;
use Carbon\Carbon;
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
        $startDate = isset($data['startDate']) ? new Carbon($data['startDate']) : null;
        $endDate = isset($data['endDate']) ? new Carbon($data['endDate']) : null;

//        $query = BankTransaction::includeWith();
//
//        if ($startDate && $endDate) {
//            $query->whereBetween('transactionDate', [$startDate, $endDate]);
//        }
//
//        $transactions = $query->get();

        $transactions = BankTransaction::where('bankAccountId', $bankAccount->id)
//            ->whereBetween('transactionDate', [$startDate, $endDate])
            ->orderBy('transactionDate', 'desc')
            ->limit(40)
            ->get();

        return BankTransactionResource::collection($transactions);
    }

    public function show()
    {
        return new BankTransactionResource($this->transaction);
    }
}

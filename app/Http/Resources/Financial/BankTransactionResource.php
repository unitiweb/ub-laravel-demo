<?php

namespace App\Http\Resources\Financial;

use App\Http\Resources\BudgetEntryResource;
use App\Http\Resources\BudgetIncomeResource;
use App\Http\Resources\CategoryResource;
use App\Services\TokenService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankBalanceResource
 *
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed siteId
 * @property mixed bankAccessTokenId
 * @property mixed bankAccountId
 * @property mixed transactionId
 * @property mixed amount
 * @property mixed transactionDate
 * @property mixed paymentChannel
 * @property mixed isoCurrencyCode
 * @property mixed name
 * @property mixed pending
 * @property mixed category
 */
class BankTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'pending' => $this->pending,
            'category' => $this->category,
            'transactionDate' => $this->transactionDate,
            'paymentChannel' => $this->paymentChannel,
            'isoCurrencyCode' => $this->isoCurrencyCode,
            'account' => new BankAccountResource($this->whenLoaded('account')),
            'entries' => BudgetEntryResource::collection($this->whenLoaded('entries')),
            'income' => new BudgetIncomeResource($this->whenLoaded('income')),
        ];
    }
}

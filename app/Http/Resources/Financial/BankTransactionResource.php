<?php

namespace App\Http\Resources\Financial;

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
            'siteId' => $this->siteId,
            'bankAccessTokenId' => $this->bankAccessTokenId,
            'bankAccountId' => $this->bankAccountId,
            'transactionId' => $this->transactionId,
            'amount' => $this->amount,
            'transactionDate' => $this->transactionDate,
            'paymentChannel' => $this->paymentChannel,
            'isoCurrencyCode' => $this->isoCurrencyCode,
            'name' => $this->name,
            'pending' => $this->pending,
            'category' => $this->category,
            'account' => new BankAccountResource($this->whenLoaded('account')),
//            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}

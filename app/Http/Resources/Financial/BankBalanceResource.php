<?php

namespace App\Http\Resources\Financial;

use App\Services\TokenService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankBalanceResource
 *
 * @package App\Http\Resources
 *
 * @property mixed accountId
 * @property mixed available
 * @property mixed current
 * @property mixed limit
 * @property mixed isoCurrencyCode
 * @property mixed unofficialCurrencyCode
 */
class BankBalanceResource extends JsonResource
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
            'accountId' => $this->accountId,
            'available' => $this->available,
            'current' => $this->current,
            'limit' => $this->limit,
            'isoCurrencyCode' => $this->isoCurrencyCode,
            'unofficialCurrencyCode' => $this->unofficialCurrencyCode,
            'account' => new BankAccountResource($this->whenLoaded('account')),
        ];
    }
}

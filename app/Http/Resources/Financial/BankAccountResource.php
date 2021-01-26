<?php

namespace App\Http\Resources\Financial;

use App\Http\Resources\SiteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankAccountResource
 *
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed accountId
 * @property mixed bankInstitutionId
 * @property mixed name
 * @property mixed officialName
 * @property mixed type
 * @property mixed subType
 * @property mixed mask
 * @property mixed enabled
 */
class BankAccountResource extends JsonResource
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
            'accountId' => $this->accountId,
            'bankInstitutionId' => $this->bankInstitutionId,
            'name' => $this->name,
            'officialName' => $this->officialName,
            'type' => $this->type,
            'subType' => $this->subType,
            'mask' => $this->mask,
            'enabled' => (bool)$this->enabled,
            'site' => new SiteResource($this->whenLoaded('site')),
            'institution' => new BankInstitutionResource($this->whenLoaded('institution')),
            'balances' => new BankBalanceResource($this->whenLoaded('balances')),
            'transactions' => BankTransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankTransactionMatchResource
 *
 * @package App\Http\Resources
 *
 * @property int id
 * @property string entryName
 * @property string entryAmount
 * @property string transactionName
 * @property string transactionAmount
 * @property string match
 */
class BankTransactionMatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'entryName' => $this->entryName,
            'entryAmount' => $this->entryAmount,
            'transactionName' => $this->transactionName,
            'transactionAmount' => $this->transactionAmount,
            'match' => $this->match,
        ];
    }
}

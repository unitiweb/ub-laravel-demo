<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankAccessTokenResource
 *
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed requestId
 * @property mixed token
 * @property mixed itemId
 */
class BankAccessTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'requestId' => $this->requestId,
            'itemId' => $this->itemId,
            'token' => $this->token,
        ];
    }
}

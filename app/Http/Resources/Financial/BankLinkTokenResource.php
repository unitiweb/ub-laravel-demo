<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankLinkTokenResource
 *
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed requestId
 * @property mixed token
 * @property mixed expiration
 * @property mixed environment
 */
class BankLinkTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'requestId' => $this->requestId,
            'token' => $this->token,
            'expiration' => $this->expiration,
            'environment' => $this->environment,
        ];
    }
}

<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BankTransactionCollection
 *
 * @package App\Http\Resources\Financial
 *
 * @property Collection collection
 */
class BankTransactionCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}

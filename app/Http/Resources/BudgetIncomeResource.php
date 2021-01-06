<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class IncomeResource
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed siteId
 * @property mixed budgetId
 * @property mixed name
 * @property mixed dueDay
 * @property mixed amount
 * @property mixed createdAt
 * @property mixed updatedAt
 */
class BudgetIncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dueDay' => $this->dueDay,
            'amount' => $this->amount,
            'entries' => BudgetEntryResource::collection($this->whenLoaded('entries')),
        ];
    }
}

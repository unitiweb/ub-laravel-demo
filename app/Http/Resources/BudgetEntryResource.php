<?php

namespace App\Http\Resources;

use App\Http\Resources\Financial\BankTransactionResource;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class EntryResource
 *
 * @package App\Http\Resources
 *
 * @property mixed id
 * @property mixed budgetGroupId
 * @property mixed budgetIncomeId
 * @property mixed name
 * @property mixed dueDay
 * @property mixed amount
 * @property mixed autoPay
 * @property mixed url
 * @property mixed goal
 * @property mixed paid
 * @property mixed cleared
 * @property mixed order
 * @property mixed createdAt
 * @property mixed updatedAt
 */
class BudgetEntryResource extends JsonResource
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
            'budgetGroupId' => $this->budgetGroupId,
            'budgetIncomeId' => $this->budgetIncomeId,
            'name' => $this->name,
            'dueDay' => $this->dueDay,
            'amount' => $this->amount,
            'autoPay' => (bool) $this->autoPay,
            'url' => $this->url,
            'goal' => (bool) $this->goal,
            'paid' => (bool) $this->paid,
            'cleared' => (bool) $this->cleared,
            'order' => $this->order,
            'income' => new BudgetIncomeResource($this->whenLoaded('income')),
            'group' => new BudgetGroupResource($this->whenLoaded('group')),
            'transactions' => BankTransactionResource::collection($this->whenLoaded('transactions'))

//            'matches' => BankTransactionMatchResource::collection($this->whenLoaded('matches')),
        ];
    }
}

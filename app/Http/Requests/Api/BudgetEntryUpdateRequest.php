<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget entry update request
 *
 * @package App\Http\Requests\Api
 */
class BudgetEntryUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'budgetIncomeId' => 'nullable|integer',
            'budgetGroupId' => 'nullable|integer',
            'groupId' => 'sometimes|required|integer',
            'name' => 'sometimes|required|string|max:30',
            'amount' => 'sometimes|required|numeric',
            'autoPay' => 'boolean',
            'dueDay' => 'sometimes|integer|min:1|max:31',
            'url' => 'nullable|string',
            'goal' => 'boolean',
            'paid' => 'boolean',
            'cleared' => 'boolean',
            'order' => 'integer',
        ];
    }
}

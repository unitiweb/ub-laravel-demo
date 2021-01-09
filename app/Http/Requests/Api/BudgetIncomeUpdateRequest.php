<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget income update request
 *
 * @package App\Http\Requests\Api
 */
class BudgetIncomeUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:30',
            'dueDay' => 'sometimes|required|integer|min:1|max:31',
            'amount' => 'sometimes|required|numeric'
        ];
    }
}

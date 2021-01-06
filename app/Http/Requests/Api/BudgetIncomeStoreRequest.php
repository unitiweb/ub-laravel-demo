<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget income store request
 *
 * @package App\Http\Requests\Api
 */
class BudgetIncomeStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'dueDay' => 'required|integer|min:1|max:31',
            'amount' => 'required|numeric'
        ];
    }
}

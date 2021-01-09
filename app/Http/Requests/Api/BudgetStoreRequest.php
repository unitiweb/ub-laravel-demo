<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget store request
 *
 * @package App\Http\Requests\Api
 */
class BudgetStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'month' => 'required|date:Y-m-d|unique:budgets,month'
        ];
    }
}

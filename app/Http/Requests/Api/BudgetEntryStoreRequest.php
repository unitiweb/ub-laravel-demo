<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget entry store request
 *
 * @package App\Http\Requests\Api
 */
class BudgetEntryStoreRequest extends ApiFormRequest
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
            'amount' => 'required|numeric',
            'dueDay' => 'required|integer|min:1|max:31',
            'groupId' => 'integer',
            'incomeId' => 'integer',
            'autoPay' => 'boolean',
            'url' => 'string',
        ];
    }
}

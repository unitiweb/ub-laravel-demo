<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget group store request
 *
 * @package App\Http\Requests\Api
 */
class BudgetGroupStoreRequest extends ApiFormRequest
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
        ];
    }
}

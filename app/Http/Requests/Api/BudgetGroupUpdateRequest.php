<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget group update request
 *
 * @package App\Http\Requests\Api
 */
class BudgetGroupUpdateRequest extends ApiFormRequest
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
            'order' => 'sometimes|required|numeric'
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

/**
 * Validate the settings update request
 *
 * @package App\Http\Requests\Api
 */
class SettingsUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'view' => 'sometimes|string|in:incomes,groups',
            'month' => 'sometimes|date_format:Y-m-d',
        ];
    }
}

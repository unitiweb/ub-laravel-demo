<?php

namespace App\Http\Requests\Api;

/**
 * Validate the profile update request
 *
 * @package App\Http\Requests\Api
 */
class ProfileUpdateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstName' => 'sometimes|required|string|max:45',
            'lastName' => 'sometimes|required|string|max:45',
            'email' => 'sometimes|email|unique:users,email',
        ];
    }
}

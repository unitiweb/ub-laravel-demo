<?php

namespace App\Http\Requests\Api;

/**
 * Validate the profile password request
 *
 * @package App\Http\Requests\Api
 */
class ProfilePasswordRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'original' => 'required|string',
            'password' => 'required|string',
        ];
    }
}

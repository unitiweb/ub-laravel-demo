<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

/**
 * Validating the login request
 *
 * @package App\Http\Requests\Api\Auth
 */
class RefreshRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'refreshToken' => 'required|string',
        ];
    }
}

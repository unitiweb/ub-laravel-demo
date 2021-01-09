<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

/**
 * Validate the forgot password reset request
 *
 * @package App\Http\Requests\Api\Auth
 */
class ForgotPasswordResetRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
            'token' => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

/**
 * Validate the forgot password change request
 *
 * @package App\Http\Requests\Api\Auth
 */
class ForgotPasswordValidateRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'code' => 'required|string|exists:tokens,token'
        ];
    }
}

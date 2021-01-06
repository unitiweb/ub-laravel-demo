<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

/**
 * Validate the verify email request
 *
 * @package App\Http\Requests\Api\Auth
 */
class VerifyEmailRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => 'required|exists:tokens,token'
        ];
    }
}

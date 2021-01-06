<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Rules\Password;

/**
 * Validating the register request
 *
 * @package App\Http\Requests\Api\Auth
 */
class RegisterRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user.email' => 'required|email|unique:users,email',
            'user.password' => ['required', 'string', new Password],
            'user.firstName' => 'required|string|min:2|max:30',
            'user.lastName' => 'required|string|min:2|max:30',
            'site.name' => 'required|string|min:5|max:30'
        ];
    }
}

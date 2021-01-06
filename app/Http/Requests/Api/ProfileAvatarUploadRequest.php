<?php

namespace App\Http\Requests\Api;

/**
 * Validate the profile avatar upload request
 *
 * @package App\Http\Requests\Api
 */
class ProfileAvatarUploadRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => 'required|string'
        ];
    }
}

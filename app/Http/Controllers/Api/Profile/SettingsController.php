<?php

namespace App\Http\Controllers\Api\Profile;

use App\Facades\Services\AuthService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\SettingsUpdateRequest;
use App\Http\Resources\SettingsResource;
use App\Models\Settings;

/**
 * Controller to handle settings resources
 *
 * @package App\Http\Controllers\Api
 */
class SettingsController extends ApiController
{
    /**
     * Update the user's settings
     *
     * @param SettingsUpdateRequest $request
     *
     * @return SettingsResource
     */
    public function update(SettingsUpdateRequest $request): SettingsResource
    {
        $data = $request->validated();
        $user = AuthService::getUser();

        $settings = Settings::where('userId', $user->id)->firstOrFail();
        $settings->update($data);

        return new SettingsResource($settings);
    }
}

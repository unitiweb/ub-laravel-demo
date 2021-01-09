<?php

namespace App\Services;

use App\Models\Settings;
use App\Models\User;

/**
 * Settings service class for user settings tasks
 *
 * @package App\Services
 */
class SettingsService
{
    /**
     * Get the given user's settings and if the settings haven't been created yet
     * then create it and add defaults
     *
     * @param User $user
     *
     * @return void
     */
    public function checkForSettings(User $user): void
    {
        if (!Settings::where('userId', $user->id)->exists()) {
            Settings::create([
                'userId' => $user->id,
                'view' => 'incomes'
            ]);
        }
    }
}

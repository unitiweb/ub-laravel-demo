<?php

namespace App\Facades\Services;

use App\Models\User;
use Illuminate\Support\Facades\Facade;

/**
 * TokenService facade
 *
 * @package App\Facades\Services
 *
 * @method static void checkForSettings(User $user)
 */
class SettingsService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\SettingsService::class;
    }
}

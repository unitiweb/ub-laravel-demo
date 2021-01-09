<?php

namespace App\Facades\Services;

use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * AuthService facade
 *
 * @package App\Facades\Services
 *
 * @method static User getUser()
 * @method static Site getSite()
 * @method static User authenticate(string $email, string $password)
 * @method static bool validatePassword(User $user, string $password, bool $double = false)
 * @method static string hashPassword(string $password, bool $double = false)
 * @method static string decodePassword(string $password)
 * @method static Collection checkPasswordCharacters(string $password)
 */
class AuthService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\AuthService::class;
    }
}

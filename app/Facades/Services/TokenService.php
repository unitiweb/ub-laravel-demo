<?php

namespace App\Facades\Services;

use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * TokenService facade
 *
 * @package App\Facades\Services
 *
 * @method static Collection signAccessToken(User $user)
 * @method static Collection signRefreshToken(User $user)
 * @method static User verifyAuthToken(string $refreshToken, string $prefix = null)
 * @method static Token createCode(User $user, string $type, int $length = 8, bool $allowMultiple = false, int $expiresAt = null)
 * @method static bool validateCode($user, string $EMAIL_VERIFY, $token)
 */
class TokenService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\TokenService::class;
    }
}

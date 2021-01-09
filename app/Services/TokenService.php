<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Service class to handle token tasks
 *
 * @package App\Services
 */
class TokenService
{
    /**
     * Sign an access token
     *
     * @param User $user The user to sign for
     *
     * @return Collection
     */
    public function signAccessToken(User $user): Collection
    {
        $expires = (int) config('tokens.access_expires');

        return collect([
            'ttl' => $expires,
            'token' => $this->signAuthToken($user, $expires)
        ]);
    }

    /**
     * Sign a refresh token
     *
     * @param User $user The user to sign for
     *
     * @return Collection
     * @throws Exception
     */
    public function signRefreshToken(User $user): Collection
    {
        $expires = (int) config('tokens.refresh_expires');;
        $token = $this->signAuthToken($user, $expires);

        $expiresDateTime = (new Carbon)->addSeconds($expires);

        // Delete all expired refresh tokens
        $this->removeOverMaxAllowedTokens(
            $user,
            Token::TYPE_REFRESH,
            config('tokens.max_concurrent_logins')
        );

        // Add the access token to the database
        Token::create([
            'userUid' => $user->uid,
            'tokenType' => Token::TYPE_REFRESH,
            'token' => $token,
            'expiresAt' => $expiresDateTime,
        ]);

        return collect([
            'ttl' => $expires,
            'token' => $token
        ]);
    }

    /**
     * Sign a user token for the given user and expiration
     *
     * @param User $user The user to sign for
     * @param int $expiration The token expiration
     *
     * @return string
     */
    public function signAuthToken(User $user, int $expiration): string
    {
        $payload = [
            'iss' => config('tokens.issuer'), // Issuer of the token
            'sub' => $user->uid, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + $expiration // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, config('tokens.secret'), config('tokens.algorithm'));
    }

    /**
     * Verify the auth token and return the user if auth token is valid
     *
     * @param string $jwt The Json Web Token
     * @param string|null $prefix If the token should have a prefix (ie Bearer) then add it here
     *
     * @return User|null
     * @throws AuthorizationException
     */
    public function verifyAuthToken(string $jwt, string $prefix = null): ?User
    {
        if ($prefix) {
            if (substr(strtolower($jwt), 0, strlen($prefix)) === strtolower($prefix)) {
                $jwt = substr($jwt, strlen($prefix));
            }
        }

        try {
            $decoded = JWT::decode($jwt, config('tokens.secret'), [config('tokens.algorithm')]);
        } catch (SignatureInvalidException $ex) {
            throw new AuthorizationException;
        } catch (ExpiredException $ex) {
            throw new AuthorizationException();
        } catch (Exception $ex) {
            throw $ex;
        }

        return User::where('uid', $decoded->sub)->first();
    }

    /**
     * Remove the old tokens that are over the max allowed and have not expired
     *
     * @param User $user The user
     * @param string $tokenType The token type
     * @param int $maxAllowed The max allowed tokens
     *
     * @return void
     */
    public function removeOverMaxAllowedTokens(User $user, string $tokenType, int $maxAllowed): void
    {
        // Remove all expired tokens
        $this->removeExpiredTokens($user, $tokenType);

        // Get all tokens for the given user and order by created_at desc
        $tokens = Token::where('userUid', $user->uid)
            ->where('tokenType', $tokenType)
            ->orderBy('createdAt', 'desc')
            ->get();

        $c = 1;
        foreach ($tokens as $token) {
            if ($c >= $maxAllowed) {
                $token->delete();
            }
            $c++;
        }
    }

    /**
     * Remove all expired tokens for the given user and given token type
     *
     * @param User $user The user
     * @param string|null $tokenType The token type
     *
     * @return void
     */
    public function removeExpiredTokens(User $user, string $tokenType = null): void
    {
        Token::where('userUid', $user->uid)
            ->where('tokenType', $tokenType)
            ->where('expiresAt', '<', Carbon::now())
            ->delete();
    }

    /**
     * Create a token code used for various forms of validation
     *
     * @param User $user The user for which to create the code
     * @param string $type The type of code to create
     * @param int $length The length for the code
     * @param bool $allowMultiple Allow multiple tokens to be valid
     * @param int|null $expiresAt The length the token will be valid in number of seconds
     *
     * @return Token
     */
    public function createCode(User $user, string $type, int $length = 8, bool $allowMultiple = false, int $expiresAt = null): Token
    {
        $this->removeExpiredTokens($user, $type);

        if (!$allowMultiple) {
            Token::where('userUid', $user->uid)
                ->where('tokenType', $type)
                ->delete();
        }

        if ($expiresAt === null) {
            $expiresAt = (new Carbon)->addSeconds((int)config('tokens.code.expires_at'));
        }

        return Token::create([
            'userUid' => $user->uid,
            'tokenType' => $type,
            'token' => Str::random($length),
            'expiresAt' => $expiresAt,
        ]);
    }

    /**
     * Validate the user's code
     *
     * @param User $user The user for the token
     * @param string $type The token type
     *
     * @return bool
     * @throws Exception
     */
    public function validateCode(User $user, string $type): bool
    {
        // Remove any expired tokens
        $this->removeExpiredTokens($user, $type);

        if (!$token = Token::where(['userUid' => $user->uid, 'tokenType' => $type])->first()) {
            // Token either doesn't exist or was removed because it has expired
            return false;
        }

        // All is good so remove the token since it can only be used once and return true
        $token->delete();

        return true;
    }
}

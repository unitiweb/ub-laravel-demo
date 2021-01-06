<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Auth\AuthCodeException;
use App\Exceptions\Auth\InvalidAuthTokenException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\UserNotVerifiedException;
use App\Facades\Services\AuthService;
use App\Facades\Services\SettingsService;
use App\Facades\Services\TokenService;
use App\Http\Requests\Api\Auth\EmailAvailableRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RefreshRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\VerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Mail\EmailValidationCode;
use App\Mail\Registration;
use App\Models\Site;
use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Login related requests
 *
 * @package App\Http\Controllers\Auth
 */
class AuthController extends ApiController
{
    /**
     * Login the user and return tokens and other relevant data
     *
     * @param LoginRequest $request
     *
     * @return JsonResource
     * @throws InvalidCredentialsException
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResource
    {
        $data = $request->validated();

        if (!$user = AuthService::authenticate($data['email'], $data['password'])) {
            throw new InvalidCredentialsException;
        }

        if (!$user->verified) {
            Mail::to($user->email)->send(new EmailValidationCode($user));
            throw new UserNotVerifiedException;
        }

        SettingsService::checkForSettings($user);

        $user->load(['site', 'settings']);

        return (new UserResource($user))->additional([
            'tokens' => [
                'access' => TokenService::signAccessToken($user),
                'refresh' => TokenService::signRefreshToken($user),
            ],
        ]);
    }

    /**
     * Try to refresh a user's login with the given refresh token
     *
     * @param RefreshRequest $request
     *
     * @return JsonResource
     * @throws InvalidAuthTokenException
     */
    public function refresh(RefreshRequest $request): JsonResource
    {
        $data = $request->validated();

        try {
            $user = TokenService::verifyAuthToken($data['refreshToken']);
        } catch (Exception $ex) {
            throw new InvalidAuthTokenException;
        }

        $user->load(['site', 'settings']);

        return (new UserResource($user))->additional([
            'tokens' => [
                'access' => TokenService::signAccessToken($user),
                'refresh' => TokenService::signRefreshToken($user),
            ],
        ]);
    }

    /**
     * Register a new user and all supporting models
     *
     * @param RegisterRequest $request
     *
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = $data['user'];
            $site = $data['site'];

            $site = Site::create(['name' => $site['name']]);

            // Decode the password before it is hashed
            $password = AuthService::decodePassword($user['password']);

            $newUser = User::create([
                'siteId' => $site->id,
                'email' => $user['email'],
                'password' => AuthService::hashPassword($password, true),
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName'],
                'status' => User::STATUS_PENDING,
                'role' => User::ROLE_OWNER,
            ]);

            SettingsService::checkForSettings($newUser);

            return $newUser;
        });

        Mail::to($user->email)->send(new Registration($user));

        return new UserResource($user);
    }

    /**
     * Verify an email address with the given code
     *
     * @param VerifyEmailRequest $request
     *
     * @return Response
     * @throws AuthCodeException
     */
    public function verifyEmail(VerifyEmailRequest $request): Response
    {
        $data = $request->validated();

        $token = Token::where('token', $data['code'])->firstOrFail();
        $user = User::where('uid', $token->userUid)->firstOrFail();

        if (!TokenService::validateCode($user, Token::EMAIL_VERIFY, $token->token)) {
            throw new AuthCodeException();
        }

        $user->verified = true;
        $user->status = User::STATUS_ACTIVE;

        // If this is a change email validation then clear out the emailChange and set the new email
        if ($user->emailChange) {
            $user->email = $user->emailChange;
            $user->emailChange = null;
        }

        $user->save();

        return response()->noContent();
    }

    /**
     * Check if the supplied email is available or not
     * If found return status code 204 (No Content)
     * If not found return status code 422 (Unprocessable Entity)
     *
     * @param EmailAvailableRequest $request
     *
     * @return Response
     */
    public function emailAvailable(EmailAvailableRequest $request): Response
    {
        return response()->noContent();
    }
}

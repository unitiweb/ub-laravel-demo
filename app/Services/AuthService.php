<?php

namespace App\Services;

use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Service class to handle authentication tasks
 *
 * @package App\Services
 */
class AuthService
{
    /**
     * Get the authenticated user
     *
     * @return User
     */
    public function getUser(): User
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $user;
    }

    /**
     * Get the authenticated user's site
     *
     * @return Site
     */
    public function getSite(): Site
    {
        $user = $this->getUser();

        return $user->site;
    }

    /**
     * Get the user by email and password. Throw exceptions if user is not found
     *
     * @param string $email The user's email
     * @param string $password The user's password
     *
     * @return User|null
     */
    public function authenticate(string $email, string $password): ?User
    {
        // Get the user by email
        if (!$user = User::where('email', $email)->first()) {
            // Since no user was found try the emailChange field
            if (!$user = User::where('emailChange', $email)->first()) {
                return null;
            }
        }

        // Check if the password is valid
        if (!$this->validatePassword($user, $password)) {
            return null;
        }

        return $user;
    }

    /**
     * Take in the raw password, and optional hashed password and check validity
     * If the hashed password is not given the authenticated user's password will be used
     *
     * @param User $user
     * @param string $password The raw password
     * @param bool $double Whether or not to double has the password
     * @return bool
     */
    public static function validatePassword(User $user, string $password, bool $double = false): bool
    {
        // Hashed passwords also sha256 hashed first
        $passwordHash = hash('sha256', $password);

        // Double is second time if $double is true
        if ($double) {
            $passwordHash = hash('sha256', $password);
        }

        // Check the password
        return Hash::check($passwordHash, $user->password);
    }

    /**
     * Hash the given password
     *
     * @param string $password The raw password*
     * @param bool $double Whether or not to double has the password
     *
     * @return string
     */
    public static function hashPassword(string $password, bool $double = false): string
    {
        // Double hash passwords
        $password = hash('sha256', $password);

        // Double is second time if $double is true
        if ($double) {
            $password = hash('sha256', $password);
        }

        return Hash::make($password);
    }

    /**
     * This is used when a password is passed into the api and decodes it
     *
     * @param string $password Encoded password
     *
     * @return string
     */
    public function decodePassword(string $password): string
    {
        // Decode the password and remove password padding
        $password = base64_decode($password);
        return substr($password, 3, -3);
    }

    /**
     * Test the characters in the password and return a collection of missing character types
     * If the collection doesn't have any entries then the password passed
     *
     * @param string $password The password to test
     *
     * @return Collection
     */
    public function checkPasswordCharacters(string $password): Collection
    {
        $result = collect();

        // Make sure the password is at least 8 characters long
        if (strlen($password) < 8) {
            $result->push('min 8 characters');
        }

        // Make sure there's at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $result->push('one lowercase');
        }

        // Make sure there's at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $result->push('one uppercase');
        }

        // Make sure there's at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $result->push('one number');
        }

        // Make sure there's at least one number
        // ToDo: If we decide to also include symbols uncomment out the lines below
        // $symbols = '@$!%*#?&';
        //     if (!preg_match('/[' . $symbols . ']/', $password)) {
        //     $result->push("one special character ($symbols)");
        // }

        return $result;
    }
}

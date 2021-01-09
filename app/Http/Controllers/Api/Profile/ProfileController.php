<?php

namespace App\Http\Controllers\Api\Profile;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\JsonError;
use App\Exceptions\UnprocessableEntryException;
use App\Facades\Services\AuthService;
use App\Facades\Services\StringService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProfileAvatarUploadRequest;
use App\Http\Requests\Api\ProfilePasswordRequest;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\Api\SiteUpdateRequest;
use App\Http\Resources\SiteResource;
use App\Http\Resources\UserResource;
use App\Mail\ChangeEmail;
use App\Mail\EmailValidationCode;
use App\Mail\Registration;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Controller to handle profile resources
 *
 * @package App\Http\Controllers\Api
 */
class ProfileController extends ApiController
{
    /**
     * Update user's profile information
     *
     * @param ProfileUpdateRequest $request
     *
     * @return UserResource
     */
    public function update(ProfileUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = AuthService::getUser();

        if ($data['email'] ?? null) {
            $data['verified'] = false;
            $data['emailChange'] = $data['email'];
            unset($data['email']);
        }

        $user->update($data);

        // If the email was part of the update then send the email validation code email
        if (isset($data['emailChange'])) {
            Mail::to($data['emailChange'])->send(new ChangeEmail($user));
        }

        return new UserResource($user);
    }

    /**
     * Change a user's password
     *
     * @param ProfilePasswordRequest $request
     *
     * @return Response
     * @throws InvalidCredentialsException
     */
    public function updatePassword(ProfilePasswordRequest $request): Response
    {
        $data = $request->validated();

        // Get authenticated user
        $user = AuthService::getUser();

        // Check if the original password is valid
        if (!AuthService::validatePassword($user, $data['original'])) {
            throw new InvalidCredentialsException;
        }

        // Passwords are double hashed then encrypted
        // The posted password should be hashed then hash again
        $user->password = AuthService::hashPassword($data['password']);
        $user->save();

        return response()->noContent();
    }

    /**
     * Store and upload the user's avatar
     *
     * @param ProfileAvatarUploadRequest $request
     *
     * @return UserResource
     * @throws UnprocessableEntryException
     */
    public function uploadAvatar(ProfileAvatarUploadRequest $request): UserResource
    {
        $data = $request->validated();

        if (!$image = StringService::parseBase64Image($data['image'])) {
            throw new UnprocessableEntryException('The base64 image format is not valid');
        }

        // Get authenticated user
        $user = AuthService::getUser();
        $path = config('filesystems.disks.remote.folders.avatars') . '/';

        // Upload avatar
        Storage::disk('remote')->put(
            $path . $image->get('filename'),
            base64_decode($image->get('image')),
            [
                'ACL' => 'public-read',
                'ContentEncoding' => $image->get('encoding'),
                'ContentType' => $image->get('mimeType'),
            ]
        );

        // Remove old avatar is one exists
        if ($user->avatar) {
            Storage::disk('remote')->delete($path . $user->avatar);
        }

        // Save new avatar
        $user->avatar = $image->get('filename');
        $user->save();

        return new UserResource($user);
    }

    /**
     * Update the user's site
     *
     * @param SiteUpdateRequest $request
     *
     * @return SiteResource
     */
    public function updateSite(SiteUpdateRequest $request): SiteResource
    {
        $data = $request->validated();

        $site = AuthService::getSite();
        $site->name = $data['name'];
        $site->save();

        return new SiteResource($site);
    }
}

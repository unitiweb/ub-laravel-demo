<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailTakenException;
use App\Exceptions\JsonError;
use App\Exceptions\RegistrationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\Registration;
use App\Models\Site;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /**
     * @param Request $request
     *
     * @return void
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|string',
            'user.firstName' => 'required|string|max:45',
            'user.lastName' => 'required|string|max:45',
            'site.name' => 'required|string|max:30'
        ]);

        $user = $data['user'];
        $site = $data['site'];

        User::create([
            'name' => $data['user']['firstName'] . ' ' . $data['user']['lastName'],
            'email' => $data['user']['email'],
            'password' => $data['user']['password'],
        ]);

//        $user = DB::transaction(function () use ($user, $site) {
//            // Create site
//            $site = factory(Site::class)->create([
//                'name' => $site['name']
//            ]);
//
//            // Create user
//            return factory(User::class)->create([
//                'siteId' => $site->id,
//                'email' => $user['email'],
//                'password' => UserService::hashPassword($user['password']),
//                'firstName' => $user['firstName'],
//                'lastName' => $user['lastName'],
//                'role' => User::ROLE_OWNER,
//            ]);
//        });
//
//        if (!$user) {
//            throw new JsonError(
//                'Registration Failed',
//                Response::HTTP_INTERNAL_SERVER_ERROR,
//                ['site' => $site, 'user' => $user]
//            );
//        }
//
//        // Send the registration welcome email
//        Mail::to($user->email)->send(new Registration($user));
//
//        return new UserResource($user);
    }

    /**
     * Check if the supplied email is available or not
     * If found return status code 204 (No Content)
     * If not found return status code 404 (Not Found)
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function emailAvailable(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
        ]);

        if (User::where('email', $data['email'])->exists() === false) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

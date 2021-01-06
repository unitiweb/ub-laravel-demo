<?php

namespace App\Providers;

use App\Exceptions\ApiAbort;
use App\Exceptions\Auth\AuthenticationException;
use App\Models\User;
use App\Facades\Services\TokenService;
use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * @throws AuthenticationException
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define the bearer jwt guards driver
        // The driver is set in the config/auth.php file
        Auth::viaRequest('bearer-jwt', function (Request $request) {
            // If the authorization header exists then try to validate the jwt token
            if ($request->hasHeader('Authorization')) {
                $token = $request->header('Authorization');
                try {
                    return TokenService::verifyAuthToken($token, 'Bearer ');
                } catch (Exception $ex) {
                    throw new AuthenticationException($ex->getMessage(), 401);
                }
            }

            return null;
        });
    }
}

<?php

use App\Exceptions\ApiRouteNotFoundException;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\BudgetEntryController;
use App\Http\Controllers\Api\BudgetGroupController;
use App\Http\Controllers\Api\BudgetIncomeController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\SettingsController;
use App\Mail\Registration;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Auth related requests
 * These requests do not require authentication
 */
Route::group(['prefix' => 'auth', 'excluded_middleware' => ['auth:api']], function() {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify-email');
    Route::post('/email-available', [AuthController::class, 'emailAvailable'])->name('auth.email-available');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('/forgot-password-validate', [AuthController::class, 'forgotPasswordValidate'])->name('auth.forgot-password-validate');
    Route::post('/forgot-password-reset', [AuthController::class, 'forgotPasswordReset'])->name('auth.forgot-password-reset');
});

Route::apiResource('budgets', BudgetController::class)->only('show', 'store', 'destroy');

Route::name('budgets.')->group(function () {
    Route::apiResource('budgets/{budget}/entries', BudgetEntryController::class);
    Route::apiResource('budgets/{budget}/incomes', BudgetIncomeController::class);
    Route::apiResource('budgets/{budget}/groups', BudgetGroupController::class);
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');
    Route::patch('/site', [ProfileController::class, 'updateSite'])->name('site.update');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings');
});


Route::get('/mailable', function () {
    $user = \App\Facades\Services\AuthService::getUser();
    return new Registration($user);
});

/**
 * A catch all api route if none above are matched
 */
Route::get('/{any}', function () {
    throw new ApiRouteNotFoundException;
})->where('any', '.*');

<?php

use App\Http\Controllers\WebhookController;
use App\Http\Middleware\FinancialWebhookMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::post('/plaid/{siteId}', [WebhookController::class, 'plaid'])->name('webhook.plaid');

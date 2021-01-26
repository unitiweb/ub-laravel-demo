<?php

namespace App\Providers;

use App\Financials\Plaid\PlaidClient;
use Illuminate\Support\ServiceProvider;

/**
 * Class PlaidProvider
 * @package App\Providers
 */
class PlaidProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PlaidClient::class, function ($app) {
            return new PlaidClient();
        });
    }
}

<?php

namespace App\Financials\Traits;

use App\Financials\FinancialClientInterface;
use Exception;

trait FinancialTraits
{
    /**
     * Instantiate and return the configured financial driver
     *
     * @return FinancialClientInterface
     * @throws Exception
     */
    protected function loadDriver(): FinancialClientInterface
    {
        $driver = config('financial.driver');
        $class = config("financial.$driver.driver");
        $config = config("financial.$driver.config");

        return new $class($config);
    }
}

<?php

namespace App\Services\Financial;

use App\Financials\Financial;
use App\Models\BankAccessToken;

/**
 * Help with command bank account processes
 *
 * @package App\Services\Financial\BankAccountService
 */
class BankAccountService
{
    /**
     * Take in the optional bank access token and update all the local bank accounts
     * with data from the remote bank accounts
     *
     * @param BankAccessToken|null $token
     */
    public function consolidate(BankAccessToken $token = null)
    {
        // If a token was supplied then make an array with only that one token and update
        if ($token) {
            $tokens = [$token];
        } else {
            $tokens = BankAccessToken::siteOnly()->get();
        }

        foreach ($tokens as $token) {
            $accounts = (new Financial)->getAccounts();
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Financials\Financial;
use App\Models\BankAccessToken;

class TempController
{
    public function temp(Financial $financial)
    {
        $bankAccessToken = BankAccessToken::where('id', 1)->first();

        $financial->accountsSync($bankAccessToken);
        $financial->transactionsSync($bankAccessToken);
        $financial->categoriesSync($bankAccessToken);

        return ['success' => true];
    }
}

<?php

namespace App\Jobs;

use App\Financials\Financial;
use App\Financials\Plaid\Webhooks\Syncs\AccountsSync;
use App\Financials\Plaid\Webhooks\Syncs\CategoriesSync;
use App\Financials\Plaid\Webhooks\Syncs\TransactionsSync;
use App\Models\BankAccessToken;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class FinancialSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var BankAccessToken
     */
    protected $bankAccessToken;

    /**
     * Create a new job instance.
     *
     * @param BankAccessToken $bankAccessToken
     */
    public function __construct(BankAccessToken $bankAccessToken)
    {
        $this->bankAccessToken = $bankAccessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $financial = App::make(Financial::class);
        $financial->accountsSync($this->bankAccessToken);
        $financial->transactionsSync($this->bankAccessToken);
        $financial->categoriesSync($this->bankAccessToken);
    }
}

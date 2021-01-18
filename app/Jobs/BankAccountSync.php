<?php

namespace App\Jobs;

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

class BankAccountSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $bankAccessTokenId;

    /**
     * Create a new job instance.
     *
     * @param int $bankAccessTokenId
     */
    public function __construct(int $bankAccessTokenId)
    {
        $this->bankAccessTokenId = $bankAccessTokenId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle()
    {
        if ($bankAccessToken = BankAccessToken::where('id', $this->bankAccessTokenId)->first()) {
            (new AccountsSync($bankAccessToken))->sync();
            (new TransactionsSync($bankAccessToken))->sync();
            (new CategoriesSync($bankAccessToken))->sync();
        }
    }
}

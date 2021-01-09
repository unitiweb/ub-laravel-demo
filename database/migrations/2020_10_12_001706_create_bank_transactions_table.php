<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankTransactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('bankAccessTokenId');
            $table->unsignedBigInteger('bankAccountId');
            $table->string('transactionId', 128)->unique();
            $table->unsignedBigInteger('categoryId')->nullable();
            $table->string('category', 45)->nullable();
            $table->float('amount')->nullable();
            $table->date('transactionDate')->nullable();
            $table->string('paymentChannel', 50)->nullable();
            $table->string('isoCurrencyCode', 8)->nullable();
            $table->string('name', 128)->nullable();
            $table->boolean('pending')->nullable();
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('bankAccessTokenId')->references('id')->on('bankAccessTokens');
            $table->foreign('bankAccountId')->references('id')->on('bankAccounts');
            $table->foreign('categoryId')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankTransactions', function (Blueprint $table) {
            $table->dropForeign('banktransactions_siteid_foreign');
            $table->dropForeign('banktransactions_bankaccesstokenid_foreign');
            $table->dropForeign('banktransactions_bankaccountid_foreign');
        });

        Schema::dropIfExists('bankTransactions');
    }
}

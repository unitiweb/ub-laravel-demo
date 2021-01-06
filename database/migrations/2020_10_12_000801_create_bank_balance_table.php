<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankBalances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('bankAccountId');
            $table->float('available')->nullable();
            $table->float('current')->nullable();
            $table->float('limit', 128)->nullable();
            $table->string('isoCurrencyCode', 8)->nullable();
            $table->string('unofficialCurrencyCode', 8)->nullable();
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('bankAccountId')->references('id')->on('bankAccounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankBalances', function (Blueprint $table) {
            $table->dropForeign('bankbalances_siteid_foreign');
            $table->dropForeign('bankbalances_bankaccountid_foreign');
        });

        Schema::dropIfExists('bankBalances');
    }
}

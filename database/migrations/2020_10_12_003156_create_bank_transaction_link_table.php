<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankTransactionLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankTransactionLink', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('budgetId');
            $table->unsignedBigInteger('budgetEntryId');
            $table->unsignedBigInteger('bankTransactionId');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('budgetId')->references('id')->on('budgets');
            $table->foreign('budgetEntryId')->references('id')->on('budgetEntries');
            $table->foreign('bankTransactionId')->references('id')->on('bankTransactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankTransactionLink', function (Blueprint $table) {
            $table->dropForeign('banktransactionlink_siteid_foreign');
            $table->dropForeign('banktransactionlink_budgetid_foreign');
            $table->dropForeign('banktransactionlink_budgetentryid_foreign');
            $table->dropForeign('banktransactionlink_banktransactionid_foreign');
        });

        Schema::dropIfExists('bankTransactionLink');
    }
}

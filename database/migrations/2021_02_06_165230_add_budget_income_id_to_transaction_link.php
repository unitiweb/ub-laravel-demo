<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgetIncomeIdToTransactionLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bankTransactionLink', function (Blueprint $table) {
            $table->unsignedBigInteger('budgetEntryId')->nullable()->change();
            $table->unsignedBigInteger('budgetIncomeId')->nullable()->after('budgetEntryId');
            $table->foreign('budgetIncomeId')->references('id')->on('budgetIncomes');
        });

        Schema::table('budgetIncomes', function (Blueprint $table) {
            $table->unsignedBigInteger('bankTransactionLinkId')->nullable()->after('budgetId');
            $table->foreign('bankTransactionLinkId')->references('id')->on('bankTransactionLink');
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
            $table->unsignedBigInteger('budgetEntryId')->nullable(false)->change();
            $table->dropForeign('banktransactionlink_budgetincomeid_foreign');
            $table->dropColumn('budgetIncomeId');
        });

        Schema::table('budgetIncomes', function (Blueprint $table) {
            $table->dropForeign('budgetincomes_banktransactionlinkid_foreign');
            $table->dropColumn('bankTransactionLinkId');
        });
    }
}

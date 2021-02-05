<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankAccountIdToBankTransactionMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bankTransactionMatch', function (Blueprint $table) {
            $table->unsignedBigInteger('bankAccountId')->after('siteId');
            $table->foreign('bankAccountId')->references('id')->on('bankAccounts');
        });

        Schema::table('budgetEntries', function (Blueprint $table) {
            $table->unsignedBigInteger('bankTransactionLinkId')->nullable()->after('categoryId');
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
        Schema::table('bankTransactionMatch', function (Blueprint $table) {
            $table->dropForeign('banktransactionmatch_bankaccountid_foreign');
            $table->dropColumn('bankAccountId');
        });

        Schema::table('budgetEntries', function (Blueprint $table) {
            $table->dropForeign('budgetentries_banktransactionlinkid_foreign');
            $table->dropColumn('bankTransactionLinkId');
        });
    }
}

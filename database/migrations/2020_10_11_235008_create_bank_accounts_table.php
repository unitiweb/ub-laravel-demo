<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankAccounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('bankAccessTokenId');
            $table->unsignedBigInteger('bankInstitutionId');
            $table->string('accountId', 128);
            $table->string('mask', 10)->nullable();
            $table->string('name', 128)->nullable();
            $table->string('officialName')->nullable();
            $table->string('subType', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->boolean('enabled')->default(false);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('bankAccessTokenId')->references('id')->on('bankAccessTokens');
            $table->foreign('bankInstitutionId')->references('id')->on('bankInstitutions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankAccounts', function (Blueprint $table) {
            $table->dropForeign('bankaccounts_siteid_foreign');
            $table->dropForeign('bankaccounts_bankaccesstokenid_foreign');
            $table->dropForeign('bankaccounts_bankinstitutionid_foreign');
        });

        Schema::dropIfExists('bankAccounts');
    }
}

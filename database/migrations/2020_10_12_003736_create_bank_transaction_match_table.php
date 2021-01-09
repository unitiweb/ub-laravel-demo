<?php

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankTransactionMatch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->string('entryName', 128);
            $table->float('entryAmount');
            $table->string('transactionName', 128);
            $table->float('transactionAmount');
            $table->string('match');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
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
            $table->dropForeign('banktransactionmatch_siteid_foreign');
        });

        Schema::dropIfExists('bankTransactionMatch');
    }
}

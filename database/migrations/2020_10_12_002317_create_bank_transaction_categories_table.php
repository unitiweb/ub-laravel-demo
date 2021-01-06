<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankTransactionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankTransactionCategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('bankTransactionId');
            $table->unsignedBigInteger('categoryId');
            $table->string('name', '45');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('bankTransactionId')->references('id')->on('bankTransactions');
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
        Schema::table('bankTransactionCategories', function (Blueprint $table) {
            $table->dropForeign('banktransactioncategories_siteid_foreign');
            $table->dropForeign('banktransactioncategories_banktransactionid_foreign');
            $table->dropForeign('banktransactioncategories_categoryid_foreign');
        });

        Schema::dropIfExists('categories');
    }
}

<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('groupId')->nullable();
            $table->unsignedBigInteger('categoryId')->nullable();
            $table->string('name', 30);
            $table->string('creditor', 60)->nullable();
            $table->integer('dueYear')->nullable();
            $table->integer('dueMonth')->nullable();
            $table->integer('dueDay')->nullable();
            $table->float('amount');
            $table->string('url')->nullable();
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('groupId')->references('id')->on('groups');
            $table->foreign('categoryId')->references('id')->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign('futureentries_siteid_foreign');
            $table->dropForeign('futureentries_groupid_foreign');
            $table->dropForeign('futureentries_categoryid_foreign');
        });

        Schema::dropIfExists('bills');
    }
}

<?php

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhookLog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->string('identifier', 128);
            $table->string('message', 255)->nullable();
            $table->timestamp('executedAt');
            $table->timestamp('availableAt');
            $table->integer('totalRecords')->default(0);
            $table->boolean('success')->default(false);
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
        Schema::table('webhookLog', function (Blueprint $table) {
            $table->dropForeign('webhooklog_siteid_foreign');
        });

        Schema::dropIfExists('webhookLog');
    }
}

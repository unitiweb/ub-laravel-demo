<?php

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userId');
            $table->string('view', 30)->default('groups');
            $table->string('month', 10)->nullable();
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign('settings_userid_foreign');
        });

        Schema::dropIfExists('settings');
    }
}

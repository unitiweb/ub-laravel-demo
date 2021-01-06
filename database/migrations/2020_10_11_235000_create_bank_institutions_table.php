<?php

use App\Models\BaseModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateBankInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankInstitutionDetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('institutionId', 40)->nullable();
            $table->string('name', 128)->nullable();
            $table->string('url', 255)->nullable();
            $table->text('logo')->nullable();
            $table->boolean('mediaPermission')->default(false);
            $table->string('primaryColor', 30)->nullable();
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
        });

        Schema::create('bankInstitutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('bankInstitutionDetailId');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('bankInstitutionDetailId')->references('id')->on('bankInstitutionDetails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bankInstitutions', function (Blueprint $table) {
            $table->dropForeign('bankinstitutions_siteid_foreign');
            $table->dropForeign('bankinstitutions_bankinstitutiondetailid_foreign');
        });

        Schema::dropIfExists('bankInstitutionDetails');
        Schema::dropIfExists('bankInstitutions');
    }
}

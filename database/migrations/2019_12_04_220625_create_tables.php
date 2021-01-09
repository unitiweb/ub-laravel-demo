<?php

use App\Models\BaseModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid')->unique();
            $table->unsignedBigInteger('siteId');
            $table->string('email', 128)->unique();
            $table->string('emailChange', 128)->nullable()->unique();
            $table->string('password', 255);
            $table->string('firstName', 45);
            $table->string('lastName', 45);
            $table->string('role', 15); // user | manager | owner
            $table->boolean('verified')->default(false);
            $table->string('status', 8)->default(User::STATUS_PENDING); // pending | active | disabled
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->index('email');
            $table->foreign('siteId')->references('id')->on('sites');
        });
        Schema::create('tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('userUid');
            $table->string('tokenType', 30); // example: access | refresh | email | etc...
            $table->string('token', 255);
            $table->text('data')->nullable();
            $table->dateTime('expiresAt');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('userUid')->references('uid')->on('users');
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('parentId')->nullable();
            $table->string('name', 45);
            $table->integer('order')->default(0);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
        });
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->string('name', 30);
            $table->integer('order')->default(1);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
        });
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->date('month');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
        });
        Schema::create('budgetIncomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('budgetId');
            $table->string('name', 30);
            $table->integer('dueDay');
            $table->float('amount');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('budgetId')->references('id')->on('budgets');
        });
        Schema::create('budgetGroups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('budgetId');
            $table->unsignedBigInteger('groupId');
            $table->string('name', 30);
            $table->integer('order')->default(1);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('budgetId')->references('id')->on('budgets');
            $table->foreign('groupId')->references('id')->on('groups');
        });
        Schema::create('budgetEntries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('budgetId');
            $table->unsignedBigInteger('budgetIncomeId')->nullable();
            $table->unsignedBigInteger('budgetGroupId')->nullable();
            $table->unsignedBigInteger('categoryId')->nullable();
            $table->string('name', 30);
            $table->integer('dueDay')->nullable();
            $table->float('amount');
            $table->boolean('autoPay')->default(false);
            $table->string('url')->nullable();
            $table->boolean('goal')->default(false);
            $table->boolean('paid')->default(false);
            $table->boolean('cleared')->default(false);
            $table->integer('order')->default(0);
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('budgetId')->references('id')->on('budgets');
            $table->foreign('budgetIncomeId')->references('id')->on('budgetIncomes');
            $table->foreign('budgetGroupId')->references('id')->on('budgetGroups');
            $table->foreign('categoryId')->references('id')->on('categories');
        });
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->datetime('transactionDate');
            $table->integer('type'); // 1: expense, 2: income
            $table->float('amount');
            $table->string('description');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
        });
        Schema::create('transactionEntries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteId');
            $table->unsignedBigInteger('transactionId');
            $table->datetime('transactionDate');
            $table->integer('type'); // 1: expense, 2: income
            $table->float('amount');
            $table->string('description');
            $table->timestamp(BaseModel::CREATED_AT)->default(Carbon::now());
            $table->timestamp(BaseModel::UPDATED_AT)->nullable();
            $table->timestamp(BaseModel::DELETED_AT)->nullable();

            $table->foreign('siteId')->references('id')->on('sites');
            $table->foreign('transactionId')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_siteid_foreign');
        });

        Schema::table('tokens', function (Blueprint $table) {
            $table->dropForeign('tokens_useruid_foreign');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign('categories_siteid_foreign');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_siteid_foreign');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign('budgets_siteid_foreign');
        });

        Schema::table('budgetIncomes', function (Blueprint $table) {
            $table->dropForeign('budgetincomes_siteid_foreign');
            $table->dropForeign('budgetincomes_budgetid_foreign');
        });

        Schema::table('budgetGroups', function (Blueprint $table) {
            $table->dropForeign('budgetgroups_siteid_foreign');
            $table->dropForeign('budgetgroups_budgetid_foreign');
            $table->dropForeign('budgetgroups_groupid_foreign');
        });

        Schema::table('budgetEntries', function (Blueprint $table) {
            $table->dropForeign('budgetentries_siteid_foreign');
            $table->dropForeign('budgetentries_budgetid_foreign');
            $table->dropForeign('budgetentries_groupid_foreign');
            $table->dropForeign('budgetentries_incomeid_foreign');
            $table->dropForeign('budgetentries_categoryid_foreign');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_siteid_foreign');
        });

        Schema::table('transactionEntries', function (Blueprint $table) {
            $table->dropForeign('transactionentries_siteid_foreign');
            $table->dropForeign('transactionentries_transactionid_foreign');
        });

        Schema::dropIfExists('sites');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tokens');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('budgetIncomes');
        Schema::dropIfExists('budgetGroups');
        Schema::dropIfExists('budgetEntries');
        Schema::dropIfExists('budgetEntries');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transactionEntries');
    }
}

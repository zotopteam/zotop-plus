<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_meta');

        Schema::create('users_meta', function (Blueprint $table) {

            $table->integer('user_id')->comment('编号')->default(0);
            $table->string('module', 64)->comment('模块名称');
            $table->string('key', 64)->comment('键名');
            $table->longText('value')->nullable()->comment('键值');
            $table->string('type', 10)->comment('类型');
            $table->timestamp('created_at')->nullable()->comment('Created_at');
            $table->timestamp('updated_at')->nullable()->comment('Updated_at');
            $table->primary(['key', 'module', 'user_id']);

            $table->comment = '';
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_meta');
    }

}

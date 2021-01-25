<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable1563784322 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {

            $table->increments('id')->comment('编号');
            $table->string('type', 128)->comment('用户类型：如super,admin或者member');
            $table->string('username', 128)->comment('用户名')->unique('username');
            $table->string('password', 255)->comment('密码');
            $table->string('email', 128)->nullable()->comment('邮箱')->unique('email');
            $table->string('mobile', 50)->nullable()->comment('手机号')->unique('mobile');
            $table->string('nickname', 100)->nullable()->comment('昵称');
            $table->boolean('gender')->comment('性别 0=保密 1=男 2=女')->default(0)->unsigned();
            $table->string('avatar', 255)->nullable()->comment('头像');
            $table->string('sign', 255)->nullable()->comment('签名');
            $table->integer('login_times')->comment('登录次数')->default(0);
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->string('login_ip', 45)->nullable()->comment('最后登录IP');
            $table->boolean('disabled')->comment('禁用 0=否 1=禁用')->default(0)->unsigned();
            $table->string('token', 255)->nullable()->comment('token');
            $table->integer('notification_count')->comment('消息通知数量')->default(0)->unsigned();
            $table->string('remember_token', 128)->nullable()->comment('remember token');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

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
        Schema::drop('users');
    }

}

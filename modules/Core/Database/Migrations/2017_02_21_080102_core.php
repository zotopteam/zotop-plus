<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Core extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // config
        Schema::create('config', function (Blueprint $table) {
            $table->string('module', 128)->comment('模块名称');
            $table->string('key', 128)->comment('键名');
            $table->text('value')->nullable()->comment('键值'); 
            $table->string('type', 10)->comment('类型');
            $table->primary(['key', 'module']);

            $table->engine = 'InnoDB';

            $table->comment = '设置';             
        });   

        // users基础表
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->string('username', 64)->unique()->comment('用户名');
            $table->string('password', 64)->comment('密码');
            $table->string('email', 200)->nullable()->unique()->comment('邮箱');
            $table->string('mobile', 50)->nullable()->unique()->comment('手机号');
            $table->string('modelid', 64)->comment('模型编号：如admin或者member');
            $table->string('nickname',100)->nullable()->comment('昵称');            
            $table->boolean('gender')->default(0)->unsigned()->comment('性别 0=保密 1=男 2=女');
            $table->string('avatar',255)->nullable()->comment('头像');
            $table->string('sign',255)->nullable()->comment('签名');            
            $table->integer('login_times')->default(0)->comment('登录次数');
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->string('login_ip', 45)->nullable()->comment('最后登录IP');
            $table->boolean('disabled')->default(0)->unsigned()->comment('禁用 0=否 1=禁用');
            $table->string('token')->nullable()->comment('token');

            $table->rememberToken();
            $table->timestamps();

            $table->comment = '用户';
        });

        // users 扩展表
        Schema::create('users_data', function (Blueprint $table) {
            $table->integer('user_id')->comment('编号');
            $table->string('module', 128)->comment('模块名称');
            $table->string('key', 128)->comment('键名');
            $table->text('value')->nullable()->comment('键值'); 
            $table->string('type', 10)->comment('类型');
            $table->timestamps();
            $table->primary(['user_id', 'module', 'key']);

            $table->engine = 'InnoDB';
            $table->comment = '用户扩展表';
        });        

        // 重设密码
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();

            $table->comment = '重设密码';
        });

        // 角色
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('角色名称');
            //$table->string('slug')->unique()->comment('机读名称');
            //$table->string('modelid', 64)->comment('角色模型：如admin或者member');
            $table->string('description')->nullable()->comment('角色描述');
            $table->text('permissions')->nullable()->comment('角色权限');
            $table->boolean('disabled')->default(0)->unsigned()->comment('禁用 0=否 1=禁用');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->comment = '角色';
        });

        // 用户角色
        Schema::create('role_users', function (Blueprint $table) {
            
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->comment = '用户角色';

            $table->primary(['user_id', 'role_id']);
        });                
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config');
        Schema::drop('users');
        Schema::drop('password_resets');
        Schema::drop('roles');
        Schema::drop('role_users');
    }
}

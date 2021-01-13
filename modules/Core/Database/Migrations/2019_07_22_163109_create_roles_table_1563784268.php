<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable1563784268 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('roles');

        Schema::create('roles', function (Blueprint $table) {

            $table->increments('id')->comment('编号');
            $table->string('type', 128)->comment('角色类型：如admin或者member');
            $table->string('name', 255)->comment('角色名称')->unique('name');
            $table->string('description', 255)->nullable()->comment('角色描述');
            $table->text('permissions')->nullable()->comment('角色权限');
            $table->boolean('disabled')->comment('禁用 0=否 1=禁用')->default(0)->unsigned();
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
        Schema::drop('roles');
    }

}

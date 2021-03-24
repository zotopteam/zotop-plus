<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavbarFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('navbar_field');
        
        Schema::create('navbar_field', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('navbar_id')->default(0)->comment('导航条编号');
            $table->bigInteger('parent_id')->default(0)->comment('导航父编号');
            $table->string('label', 100)->comment('显示的标签名称');
            $table->string('type', 100)->comment('控件类型，如text，number等');
            $table->string('name', 100)->comment('字段名称');
            $table->text('default')->nullable()->comment('默认值');
            $table->text('settings')->nullable()->comment('控件设置，如radio，select等的选项');
            $table->string('help', 255)->nullable()->comment('控件提示信息');
            $table->integer('sort')->unsigned()->nullable()->default(0)->comment('排序字段');
            $table->boolean('disabled')->nullable()->default(0)->comment('是否禁用，0：启用，1：禁用');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navbar_field');
    }
}

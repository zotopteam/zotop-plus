<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentModelTable1541339871 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content_model');
		
        Schema::create('content_model', function (Blueprint $table) {

        	$table->char('id', 64)->comment('模型ID，如：news')->primary('id');
			$table->string('icon', 255)->default('fa-file')->comment('图标');
			$table->char('name', 64)->comment('名称');
			$table->string('description', 255)->nullable()->comment('说明');
			$table->char('module', 64)->nullable()->comment('模块');
			$table->char('model', 64)->nullable()->comment('模型');
			$table->string('template', 100)->nullable()->comment('详细页面模版');
			$table->integer('posts')->nullable()->comment('数据量')->default(0)->unsigned();
			$table->tinyInteger('sort')->nullable()->comment('排序')->default(0);
			$table->tinyInteger('disabled')->nullable()->comment('禁用')->default(0);

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
		Schema::drop('content_model');
	}

}

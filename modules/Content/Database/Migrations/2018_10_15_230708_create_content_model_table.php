<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentModelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content_model');
		
        Schema::create('content_model', function (Blueprint $table) {

        	$table->char('id', 32)->primary()->comment('模型ID，如：news');
			$table->string('icon')->default('fa-file')->comment('图标');
			$table->char('name', 32)->comment('名称');
			$table->string('description')->nullable()->comment('说明');
			$table->char('app', 32)->comment('隶属应用ID');
			$table->char('model', 32)->comment('对应app中的模型');
			$table->string('template', 100)->nullable()->comment('详细页面模版');
			$table->integer('posts')->unsigned()->nullable()->default(0)->comment('数据量');
			$table->boolean('listorder')->nullable()->comment('排序');
			$table->boolean('disabled')->nullable()->default(0)->comment('禁用');

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

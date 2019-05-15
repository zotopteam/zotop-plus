<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockDatalistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('block_datalist');
		
        Schema::create('block_datalist', function (Blueprint $table) {

        	$table->increments('id')->comment('编号');
			$table->integer('block_id')->comment('区块编号');
			$table->string('source_id', 64)->nullable()->comment('数据源编号');
			$table->char('module', 64)->nullable()->comment('应用编号');
			$table->text('data')->comment('数据');
			$table->integer('user_id')->unsigned()->comment('创建人编号');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->boolean('stick')->nullable()->default(0)->comment('是否固顶，0：不固顶，1：固顶');
			$table->char('status', 10)->nullable()->comment('状态');
			$table->timestamps();
			$table->index(['block_id','sort'], 'section_id');

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
		Schema::drop('block_datalist');
	}

}

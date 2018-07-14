<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('section_category');
		
        Schema::create('section_category', function (Blueprint $table) {

        	$table->increments('id')->comment('编号');
			$table->string('name')->comment('名称');
			$table->text('description')->nullable()->comment('说明');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->integer('posts')->unsigned()->default(0)->comment('数量');
			$table->timestamps();

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
		Schema::drop('section_category');
	}

}

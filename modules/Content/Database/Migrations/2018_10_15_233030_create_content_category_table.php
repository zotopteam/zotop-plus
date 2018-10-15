<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content_category');
		
        Schema::create('content_category', function (Blueprint $table) {

        	$table->smallInteger('id')->unsigned()->primary()->comment('编号');
			$table->smallInteger('parent_id')->unsigned()->default(0)->comment('父编号');
			$table->string('name', 50)->comment('名称');
			$table->string('alias', 50)->nullable()->comment('别名/英文名');
			$table->string('title', 100)->nullable()->comment('标题');
			$table->string('image', 100)->nullable()->comment('图片');
			$table->string('keywords', 100)->nullable()->comment('关键词');
			$table->text('description')->nullable()->comment('描述');
			$table->text('settings')->nullable()->comment('其它设置');
			$table->boolean('sort')->nullable()->default(0)->comment('排序');
			$table->boolean('disabled')->nullable()->default(0)->comment('禁用');
			$table->index(['parent_id','sort','disabled'], 'parentid_listorder_disabled');

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
		Schema::drop('content_category');
	}

}

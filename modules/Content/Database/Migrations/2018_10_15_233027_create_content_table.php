<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content');
		
        Schema::create('content', function (Blueprint $table) {

        	$table->increments('id')->comment('编号');
			$table->integer('parent_id')->unsigned()->default(0)->comment('父编号');
			$table->smallInteger('category_id')->unsigned()->index('categoryid')->comment('分类');
			$table->char('model_id', 64)->comment('模型ID');
			$table->string('title', 100)->comment('标题');
			$table->string('title_style', 50)->nullable()->comment('标题样式');
			$table->string('alias', 100)->nullable()->comment('别名');
			$table->string('url', 100)->nullable()->comment('链接');
			$table->string('image')->nullable()->comment('缩略图');
			$table->string('keywords', 100)->nullable()->comment('关键词');
			$table->string('summary', 500)->nullable()->comment('摘要');
			$table->string('template', 50)->nullable()->comment('模版');
			$table->integer('hits')->unsigned()->nullable()->default(0)->comment('点击数');
			$table->boolean('comment')->nullable()->default(0)->comment('评论，1=允许，0=禁止');
			$table->smallInteger('comments')->unsigned()->nullable()->default(0)->comment('评论数');
			$table->char('status', 10)->nullable()->index('status')->comment('状态');
			$table->boolean('stick')->nullable()->default(0)->index('stick')->comment('是否固顶，0：不固顶，1：固顶');
			$table->integer('sort')->unsigned()->default(0)->index('sort')->comment('排序');
			$table->integer('user_id')->nullable()->default(0)->comment('会员编号');
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
		Schema::drop('content');
	}

}

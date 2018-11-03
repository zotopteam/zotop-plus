<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable1541233294 extends Migration
{
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
			$table->integer('parent_id')->comment('父编号')->default(0)->unsigned();
			$table->smallInteger('category_id')->comment('分类')->index('category_id')->default(0)->unsigned();
			$table->char('model_id', 64)->comment('模型ID');
			$table->string('title', 255)->comment('标题');
			$table->string('title_style', 50)->nullable()->comment('标题样式');
			$table->string('alias', 100)->nullable()->comment('别名');
			$table->string('url', 100)->nullable()->comment('链接');
			$table->string('image', 255)->nullable()->comment('缩略图');
			$table->string('keywords', 100)->nullable()->comment('关键词');
			$table->string('summary', 500)->nullable()->comment('摘要');
			$table->string('template', 50)->nullable()->comment('模版');
			$table->integer('hits')->nullable()->comment('点击数')->default(0)->unsigned();
			$table->tinyInteger('comment')->nullable()->comment('评论，1=允许，0=禁止')->default(0);
			$table->smallInteger('comments')->nullable()->comment('评论数')->default(0)->unsigned();
			$table->char('status', 10)->nullable()->comment('状态')->index('status');
			$table->tinyInteger('stick')->nullable()->comment('是否固顶，0：不固顶，1：固顶')->index('stick')->default(0)->unsigned();
			$table->integer('sort')->nullable()->comment('排序')->index('sort')->default(0)->unsigned();
			$table->integer('user_id')->nullable()->comment('会员编号')->default(0)->unsigned();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();

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

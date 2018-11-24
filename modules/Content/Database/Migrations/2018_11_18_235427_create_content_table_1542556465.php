<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable1542556465 extends Migration
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

        	$table->increments('id')->nullable(false)->comment('编号');
			$table->integer('parent_id')->nullable(false)->comment('父编号')->index('parent_id')->default(0)->unsigned();
			$table->char('model_id', 64)->nullable(false)->comment('模型ID');
			$table->string('title', 255)->nullable(false)->comment('标题');
			$table->string('title_style', 50)->nullable()->comment('标题样式');
			$table->string('slug', 255)->nullable()->comment('别名')->unique('slug');
			$table->text('image')->nullable()->comment('缩略图');
			$table->string('keywords', 100)->nullable()->comment('关键词');
			$table->string('summary', 1000)->nullable()->comment('摘要');
			$table->string('url', 255)->nullable()->comment('链接');
			$table->string('template', 100)->nullable()->comment('模版');
			$table->integer('hits')->nullable()->comment('点击数')->default(0)->unsigned();
			$table->integer('comments')->nullable()->comment('评论数')->default(0)->unsigned();
			$table->char('status', 32)->nullable()->comment('状态 publish/pending/trash')->index('status');
			$table->tinyInteger('stick')->nullable()->comment('是否固顶，0：不固顶，1：固顶')->index('stick')->default(0)->unsigned();
			$table->integer('sort')->nullable()->comment('排序')->index('sort')->default(0)->unsigned();
			$table->integer('user_id')->nullable()->comment('用户编号')->default(0)->unsigned();
			$table->timestamp('publish_at')->nullable()->comment('发布时间');
			$table->timestamp('created_at')->nullable()->comment('更新时间');
			$table->timestamp('updated_at')->nullable()->comment('创建时间');

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

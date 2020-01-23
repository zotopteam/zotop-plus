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

        	$table->increments('id')->comment('编号');
			$table->integer('parent_id')->comment('父编号')->index('parent_id')->default(0)->unsigned();
			$table->char('model_id', 64)->comment('模型ID')->index('model_id');
			$table->string('title', 255)->comment('标题');
			$table->string('title_style', 50)->nullable()->comment('标题样式');
			$table->string('slug', 255)->nullable()->comment('别名')->unique('slug');
			$table->string('image', 255)->nullable()->comment('缩略图');
			$table->string('keywords', 100)->nullable()->comment('关键词');
			$table->string('summary', 1000)->nullable()->comment('摘要');
			$table->string('link', 255)->nullable()->comment('链接');
			$table->string('view', 100)->nullable()->comment('模版');
			$table->integer('hits')->nullable()->comment('点击数')->default(0)->unsigned();
			$table->integer('comments')->nullable()->comment('评论数')->default(0)->unsigned();
			$table->char('status', 32)->nullable()->comment('状态 publish=发布||pending=草稿||trash=回收站')->index('status');
			$table->tinyInteger('stick')->nullable()->comment('是否固顶，0=不固顶||1=固顶')->index('stick')->default(0)->unsigned();
			$table->integer('sort')->nullable()->comment('排序')->index('sort')->default(0)->unsigned();
			$table->string('source_id', 64)->nullable()->comment('源数据编号')->index('source_id');
			$table->integer('user_id')->nullable()->comment('用户编号')->default(0)->unsigned();
			$table->timestamp('publish_at')->nullable()->comment('发布时间');
			$table->timestamp('created_at')->nullable()->comment('更新时间');
			$table->timestamp('updated_at')->nullable()->comment('创建时间');

            $table->comment = '内容';             
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::disableForeignKeyConstraints();
		Schema::drop('content');
	}

}

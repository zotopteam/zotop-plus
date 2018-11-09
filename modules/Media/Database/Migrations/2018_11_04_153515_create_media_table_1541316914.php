<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable1541316914 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('media');
		
        Schema::create('media', function (Blueprint $table) {

        	$table->increments('id');
			$table->integer('parent_id')->comment('父编号')->default(0)->unsigned();
			$table->string('type', 32)->comment('类型, folder/image/viedo/audio/files')->index('type');
			$table->string('name', 255)->comment('名称');
			$table->string('path', 255)->nullable()->comment('文件路径');
			$table->string('url', 255)->nullable()->comment('文件链接');
			$table->string('extension', 32)->nullable()->comment('文件扩展名');
			$table->string('mimetype', 32)->nullable()->comment('文件MimeType');
			$table->integer('width')->comment('图片宽度')->default(0)->unsigned();
			$table->integer('height')->comment('图片高度')->default(0)->unsigned();
			$table->integer('size')->comment('大小')->default(0)->unsigned();
			$table->string('module', 64)->nullable()->comment('模块');
			$table->string('controller', 64)->nullable()->comment('控制器');
			$table->string('action', 64)->nullable()->comment('动作');
			$table->string('field', 64)->nullable()->comment('字段');
			$table->string('data_id', 64)->nullable()->comment('数据编号');
			$table->integer('user_id')->comment('用户编号')->default(0);
			$table->integer('sort')->comment('排序')->index('sort')->default(0)->unsigned();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
			$table->index(['action','controller','data_id','field','module'], 'action_controller_data_id_field_module');

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
		Schema::drop('media');
	}

}

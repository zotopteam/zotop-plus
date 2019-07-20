<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTable1563467660 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('logs');
		
        Schema::create('logs', function (Blueprint $table) {

        	$table->increments('id')->comment('Id');
        	$table->string('type', 64)->comment('类型');
			$table->integer('user_id')->nullable()->comment('用户编号')->default(0)->unsigned();
			$table->string('user_ip', 45)->nullable()->comment('用户IP');
			$table->string('url', 255)->comment('地址');
			$table->string('module', 64)->comment('模块');
			$table->string('controller', 64)->comment('控制器');
			$table->string('action', 64)->comment('动作');
			$table->text('content')->comment('日志内容');
			$table->longText('request')->comment('请求数据');
			$table->longText('response')->comment('响应数据');
			$table->timestamp('created_at')->nullable()->comment('创建时间');
			$table->timestamp('updated_at')->nullable()->comment('更新时间');

            $table->comment = '日志';             
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('logs');
	}

}

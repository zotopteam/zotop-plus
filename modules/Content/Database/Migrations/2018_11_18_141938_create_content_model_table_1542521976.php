<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentModelTable1542521976 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content_model');
		
        Schema::create('content_model', function (Blueprint $table) {

        	$table->char('id', 64)->nullable(false)->comment('模型ID，如：news')->unique('id');
			$table->string('icon', 255)->nullable()->default('fa-file')->comment('图标');
			$table->char('name', 64)->nullable(false)->comment('名称');
			$table->char('table', 64)->nullable()->comment('扩展表名称');
			$table->string('description', 255)->nullable()->comment('说明');
			$table->char('module', 64)->nullable()->comment('模块');
			$table->char('model', 255)->nullable()->comment('模型');
			$table->string('template', 100)->nullable()->comment('详细页面模版');
			$table->boolean('nestable')->nullable()->comment('可嵌套，0=否 1=是')->default(0);
			$table->integer('posts')->nullable()->comment('数据量')->default(0)->unsigned();
			$table->integer('sort')->nullable()->comment('排序')->default(0);
			$table->boolean('disabled')->nullable()->comment('禁用')->default(0);
			$table->integer('user_id')->nullable()->comment('用户编号')->default(0)->unsigned();
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
		Schema::drop('content_model');
	}

}

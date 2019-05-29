<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentFieldTable1542101025 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('content_field');
		
        Schema::create('content_field', function (Blueprint $table) {

        	$table->increments('id');
			$table->char('model_id', 64)->comment('模型编号');
			$table->string('label', 100)->comment('显示的标签名称');
			$table->string('type', 100)->comment('控件类型，如text，number等');
			$table->string('name', 100)->nullable()->comment('数据库中的字段名称');
			$table->text('default')->nullable()->comment('默认值');
			$table->text('settings')->nullable()->comment('控件设置，如radio，select等的选项');
			$table->string('help', 255)->nullable()->comment('控件提示信息');
			$table->boolean('post')->nullable()->default(1)->comment('是否允许前台填写提交，0：不允许，1：允许');
			$table->boolean('search')->nullable()->comment('是否允许搜索，0：禁止，1：允许')->default(0);
			$table->boolean('system')->nullable()->comment('是否为系统字段，0：自定义字段，1：系统字段')->default(0);
			$table->string('position', 64)->nullable()->comment('控件位置，main=主区域 side=侧边区域')->default('main');
			$table->string('width', 64)->nullable()->comment('控件宽度，主区域可用')->default('w-100');
			$table->integer('sort')->nullable()->comment('排序字段')->default(0)->unsigned();
			$table->boolean('disabled')->nullable()->comment('是否禁用，0：未禁用，1：禁用')->default(0);
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
		Schema::drop('content_field');
	}

}

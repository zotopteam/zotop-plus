<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentFieldTable extends Migration {

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
			$table->char('modelid', 32)->comment('模型编号');
			$table->string('control', 100)->comment('控件类型，如text，number等');
			$table->string('label', 100)->comment('显示的标签名称');
			$table->string('name', 100)->comment('数据库中的字段名称');
			$table->char('type', 32)->comment('字段类型，如char，varchar，int等');
			$table->integer('length')->unsigned()->nullable()->comment('字段长度');
			$table->text('default')->nullable()->comment('默认值');
			$table->boolean('notnull')->nullable()->default(0)->comment('是否允许空子，0：允许，1：不允许');
			$table->boolean('unique')->nullable()->default(0)->comment('是否唯一，0：非唯一，1：必须是唯一值');
			$table->text('settings')->nullable()->comment('字段其它设置，如radio，select等的选项');
			$table->string('tips')->nullable()->comment('字段提示信息');
			$table->boolean('base')->nullable()->default(0)->comment('是否在添加编辑的左侧显示，0：否，1：是');
			$table->boolean('post')->nullable()->default(1)->comment('是否允许前台填写提交，0：不允许，1：允许');
			$table->boolean('search')->nullable()->default(0)->comment('是否允许搜索，0：禁止，1：允许');
			$table->boolean('system')->nullable()->default(0)->comment('是否为系统字段，0：自定义字段，1：系统字段');
			$table->integer('listorder')->unsigned()->nullable()->default(0)->comment('排序字段');
			$table->boolean('disabled')->nullable()->default(0)->comment('是否禁用，0：未禁用，1：禁用');

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

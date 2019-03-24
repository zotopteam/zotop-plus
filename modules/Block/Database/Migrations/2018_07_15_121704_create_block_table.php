<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('block');
		
        Schema::create('block', function (Blueprint $table) {

        	$table->increments('id')->comment('区块编号');
			$table->integer('category_id')->unsigned()->nullable()->default(0)->comment('分类编号');
			$table->char('type', 20)->default('html')->comment('类型，html:内容,list:列表,hand:手动,text:文本');
			$table->char('slug', 64)->unique('code')->comment('区块别名');
			$table->string('name', 100)->comment('区块名称');
			$table->text('description')->nullable()->comment('说明');
			$table->integer('rows')->default(0)->comment('行数，0为无限制');
			$table->text('data')->nullable()->comment('数据');
			$table->text('view')->nullable()->comment('模版');
			$table->smallInteger('interval')->nullable()->default(3600)->comment('更新频率，单位秒，0：手动更新');
			$table->text('fields')->nullable()->comment('字段设置');
			$table->boolean('commend')->default(0)->comment('是否允许推送，0：不允许，1：允许且需审核，2：允许且无需审核');
			$table->integer('sort')->unsigned()->default(0)->comment('排序');
			$table->integer('user_id')->nullable()->comment('用户编号');
			$table->boolean('disabled')->default(0)->comment('是否禁用');
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
		Schema::drop('block');
	}

}

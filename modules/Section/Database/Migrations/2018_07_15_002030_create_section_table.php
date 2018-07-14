<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('section');
		
        Schema::create('section', function (Blueprint $table) {

        	$table->increments('id')->comment('区块编号');
			$table->char('code', 64)->unique('code')->comment('区块编码');
			$table->string('name', 100)->comment('区块名称');
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
		Schema::drop('section');
	}

}

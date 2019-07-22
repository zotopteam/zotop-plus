<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable1563784201 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('password_resets');
		
        Schema::create('password_resets', function (Blueprint $table) {

        	$table->string('email', 255)->comment('Email')->index('email');
			$table->string('token', 255)->comment('Token')->index('token');
			$table->timestamp('created_at')->nullable()->comment('Created_at');

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
		Schema::drop('password_resets');
	}

}

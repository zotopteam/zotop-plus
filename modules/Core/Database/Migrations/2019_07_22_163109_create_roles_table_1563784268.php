<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable1563784268 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('roles');
		
        Schema::create('roles', function (Blueprint $table) {

        	$table->increments('id')->comment('Id');
			$table->string('name', 255)->comment('角色名称')->unique('name');
			$table->string('description', 255)->nullable()->comment('角色描述');
			$table->text('permissions')->nullable()->comment('角色权限');
			$table->boolean('disabled')->comment('禁用 0=否 1=禁用')->default(0)->unsigned();
			$table->timestamp('created_at')->nullable()->comment('Created_at');
			$table->timestamp('updated_at')->nullable()->comment('Updated_at');

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
		Schema::drop('roles');
	}

}

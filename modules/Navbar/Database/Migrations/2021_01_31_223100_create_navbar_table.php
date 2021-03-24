<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavbarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('navbar');

        Schema::create('navbar', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->comment('标识');
            $table->mediumInteger('sort')->unsigned()->default(0)->comment('排序');
            $table->boolean('disabled')->default(0)->comment('禁用');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navbar');
    }
}

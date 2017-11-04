<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Region extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //区域
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级ID');
            $table->string('title')->default('')->comment('名称');
            $table->smallInteger('sort')->unsigned()->default(0)->comment('排序');
            $table->tinyInteger('disabled')->unsigned()->default(0)->comment('启用0禁用1');

            $table->comment = '区域';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('regions');
    }
}

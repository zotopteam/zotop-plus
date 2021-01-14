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
            $table->string('title', 200)->nullable()->index('title')->comment('标题');
            $table->string('slug', 200)->nullable()->comment('链接地址');
            $table->longText('fields')->nullable()->comment('自定义字段');
            $table->integer('sort')->unsigned()->default(0)->comment('排序');
            $table->boolean('status')->default(1)->comment('状态 1=启用 0=禁用');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['sort', 'status'], 'sort_status');


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

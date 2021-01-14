<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavbarItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('navbar_item');

        Schema::create('navbar_item', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned()->default(0)->comment('父级编号');
            $table->string('title', 200)->nullable()->index('title')->comment('标题');
            $table->string('link', 200)->nullable()->comment('链接地址');
            $table->longText('custom')->nullable()->comment('自定义数据');
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
        Schema::dropIfExists('navbar_item');
    }
}

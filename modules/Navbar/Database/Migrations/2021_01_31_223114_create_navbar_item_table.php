<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigInteger('navbar_id')->unsigned()->default(0)->comment('导航条编号');
            $table->bigInteger('parent_id')->unsigned()->default(0)->comment('父级编号');
            $table->string('title', 200)->comment('标题');
            $table->string('link', 200)->nullable()->comment('链接地址');
            $table->longText('custom')->nullable()->comment('自定义数据');
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
        Schema::dropIfExists('navbar_item');
    }
}

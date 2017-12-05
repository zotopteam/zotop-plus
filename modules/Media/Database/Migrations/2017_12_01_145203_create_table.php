<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级ID');
            $table->string('name')->comment('名称');
            $table->text('settings')->nullable()->comment('设置');
            $table->smallInteger('sort')->unsigned()->default(0)->comment('排序');
            $table->tinyInteger('disabled')->unsigned()->default(0)->comment('启用0禁用1');
            $table->timestamps();
            $table->comment = '媒体文件夹';
        });

        Schema::create('media_files', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('folder_id')->unsigned()->default(0)->comment('文件夹编号');
            $table->string('name')->comment('名称');
            $table->string('path')->comment('路径');
            $table->string('url')->comment('链接');
            $table->string('type')->comment('类型, image/viedo/audio/files');
            $table->string('extension')->comment('扩展名');
            $table->string('mimetype')->comment('MimeType');
            $table->integer('width')->unsigned()->default(0)->comment('图片宽度');
            $table->integer('height')->unsigned()->default(0)->comment('图片高度');
            $table->integer('size')->unsigned()->default(0)->comment('大小');
            $table->string('module')->nullable()->comment('模块');
            $table->string('controller')->nullable()->comment('控制器');
            $table->string('action')->nullable()->comment('动作');
            $table->string('field')->nullable()->comment('字段');
            $table->string('data_id')->nullable()->comment('数据编号');
            $table->integer('user_id')->comment('用户编号');
            $table->timestamps();

            $table->engine  = 'InnoDB';
            $table->comment = '媒体文件';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('media_files');
    }
}

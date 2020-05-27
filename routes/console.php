<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

/**
 * 清理预览文件
 */
Artisan::command('preview:clear', function () {
    $dir = public_path('previews');

    // 删除预览文件夹下面的全部目录
    foreach ($this->laravel['files']->directories($dir) as $subdir) {
        $this->laravel['files']->deleteDirectory($subdir);
    }

    $this->info('Preview files cleared!');
})->describe('Clear the preview files');


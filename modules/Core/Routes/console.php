<?php
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

/**
 * 清理上传缓存
 */
Artisan::command('plupload:clear {--force}', function ($force) {
    $dir = storage_path('plupload');

    if (!$this->laravel['files']->isDirectory($dir)) {
        $this->error('Plupload temp directory does not existed!');
        return false;
    }

    foreach ($this->laravel['files']->files($dir) as $file) {
        //非强制模式下，只清除24小时以前的缓存
        if (!$force && filemtime($file) > time() - 24 * 60 * 60) {
            continue;
        }
        $this->laravel['files']->delete($file);
    }

    $this->info('Plupload temp files cleared!');
})->describe('Clear the plupload temp files');

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
 * 显示当前版本信息
 */
Artisan::command('zotop:version', function () {
    $name    = $this->laravel['config']->get('zotop.name');
    $version = $this->laravel['config']->get('zotop.version');
    $release = $this->laravel['config']->get('zotop.release');

    $this->info($name.' version:'.$version.'('.$release.')');

})->describe('Show the version of zotop');


/**
 * 设置.env值
 */
Artisan::command('env:set {key} {value}', function ($key, $value) {

    // 键名大写
    $key = strtoupper($key);

    // 获取env文件数据
    $env = app()->environmentFilePath();
    $str = file_get_contents($env)."\n";
    
    // 获取原有的行
    $start = strpos($str, "{$key}=");
    $end   = strpos($str, "\n", $start);
    $old   = substr($str, $start, $end - $start);

    // 如果不存在则追加，存在则替换
    if (!$start || !$end || !$old) {
        $str .= "{$key}={$value}\n";
    } else {
        $str = str_replace($old, "{$key}={$value}", $str);
    }

    $str = substr($str, 0, -1);

    file_put_contents($env, $str);

})->describe('set .env key value');

/**
 * 清理日志
 */
Artisan::command('log:clear', function () {
    $dir = storage_path('logs');

    // 清理log文件（保留.gitignore等隐藏文件）
    foreach ($this->laravel['files']->files($dir, false) as $file) {
        $this->laravel['files']->delete($file);
    }

    $this->info('Logs cleared!');
})->describe('Clear the log files');

/**
 * 清理图片预览
 */
Artisan::command('preview:clear', function () {
    $dir = public_path('previews');

    foreach ($this->laravel['files']->directories($dir) as $subdir) {
        $this->laravel['files']->deleteDirectory($subdir);
    }

    $this->info('Image preview files cleared!');
})->describe('Clear the image preview files');

/**
 * 清理图片缩略图
 */
Artisan::command('thumbnail:clear', function () {
    $dir = public_path('thumbnails');

    foreach ($this->laravel['files']->directories($dir) as $subdir) {
        $this->laravel['files']->deleteDirectory($subdir);
    }

    $this->info('Image thumbnail files cleared!');
})->describe('Clear the image thumbnail files');

/**
 * 清理上传缓存
 */
Artisan::command('plupload:clear {--force}', function ($force) {
    $dir = storage_path('plupload');

    if (! $this->laravel['files']->isDirectory($dir)) {
        $this->error('Plupload temp directory does not existed!');
        return false;
    }

    foreach ($this->laravel['files']->files($dir) as $file) {
        //非强制模式下，只清除24小时以前的缓存
        if (!$force && filemtime($file) > time() - 24*60*60) {
            continue;
        }
        $this->laravel['files']->delete($file);
    }

    $this->info('Plupload temp files cleared!');
})->describe('Clear the plupload temp files');

<?php
use Intervention\Image\ImageManagerStatic as Image;

$fs    = app('files');
$path  = resource_path('fonts');
$files = $fs->files($path);
$fonts = [];

// 如果字体预览目录不存在，尝试创建
if (! $fs->isDirectory($dir = public_path('previews/fonts')) ) {
    $fs->makeDirectory($dir, 0775, true);
}

// 生成字体预览图
foreach ($files as $file) {
    $path = path_base($file);
    $name = app('files')->name($file);

    $preview = 'previews/fonts/'.md5($name).'.jpg';
    if (!$fs->exists(public_path($preview))) {
        $width = 120;
        $height = 40;
        $image = Image::canvas($width, $height, '#0072c6');
        $text = trans('core::image.watermark.font');
        $image->text($text, $width/2, $height/2, function ($font) use ($path) {
            $font->file(base_path($path));
            $font->size(18);
            $font->color('#fffff');
            $font->align('center');  //left, right or center. 
            $font->valign('middle');    //top, bottom or middle.             
        });
        $image->save($preview);
    }
    $fonts[$path] = [url($preview), '', $name];
}

return $fonts;

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
    $path   = path_base($file);
    $name   = $fs->name($file);
    $size   = 24;
    $width  = 180;
    $height = 60;

    // 预览图地址
    $preview = 'previews/fonts/'.md5($name.'-'.$size.'-'.$width.'-'.$height).'.jpg';

    // 生成预览图
    if (! $fs->exists(public_path($preview))) {

        $image = Image::canvas($width, $height, '#0072c6');
        $text = trans('core::image.watermark.font');
        $image->text($text, $width/2, $height/2, function ($font) use ($path, $size) {
            $font->file(base_path($path));
            $font->size($size);
            $font->color('#fffff');
            $font->align('center');  //left, right or center. 
            $font->valign('middle');    //top, bottom or middle.             
        });
        $image->save($preview);
    }

    $fonts[$path] = [
        'image'       => url($preview),
        'tooltip'     => $name,
    ];
}

return $fonts;

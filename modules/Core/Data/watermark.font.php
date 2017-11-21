<?php
use Intervention\Image\ImageManagerStatic as Image;

$fs = app('files');
$path  = resource_path('watermark/fonts');
$files = $fs->files($path);
$fonts = [];

foreach ($files as $file) {
    $path = path_base($file);
    $name = app('files')->name($file);

    $preview = 'temp/preview/font-'.md5($name).'.jpg';
    if (!$fs->exists(public_path($preview))) {
        $width = 120;
        $height = 40;
        $image = Image::canvas($width, $height, '#0072c6');
        $text = trans('core::config.image.watermark.font');
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

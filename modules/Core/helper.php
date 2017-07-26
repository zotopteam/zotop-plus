<?php

/**
 * 预览图片
 * 
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function preview($path)
{
    if ( empty($path) || !File::exists($path) ) {
        return \Theme::asset(app('current.theme')->name.':img/placeholder.png');
    }

    $temp = 'temp/preview/'.md5($path).'.'.File::extension($path);
    $file = public_path($temp);

    // 预览图片不存在，或者原图片被修改
    if ( !File::exists($temp) OR File::lastModified($temp) < File::lastModified($path) ) {
        File::copy($path, $file);
    }  

    return url($temp);
}
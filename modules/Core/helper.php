<?php

if (! function_exists('preview')) {
    /**
     * 预览图片
     * 
     * @param  string $path 图片路径
     * @return string 临时图片URL
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
}
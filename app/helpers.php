<?php

use Illuminate\Support\Str;

if (! function_exists('dirsize')) {

    /**
     * 递归获取目录大小
     * 
     * @param  string $dir   dir path
     * @param  boolean $format 是否格式化为可读格式
     * @return string
     */
    // 递归计算文件夹大小
    function dirsize($dir, $format=true)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : dirsize($each, false);
        }
        return $format? size_format($size) : $size;
    }
}

if (! function_exists('size_format')) {

    /**
     * 格式化size为可读格式
     * 
     * @param  integer $bytes    size bytes
     * @param  integer $decimals 小数位个数
     * @return string
     */
    function size_format(int $bytes, int $decimals = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;
        while ($bytes >= 1024)
        {
            $bytes /= 1024;
            $index++;
        }
        return number_format($bytes, $decimals).' '.$units[$index];        
    }
}

if (! function_exists('path_base')) {
    /**
     *
     * 将完整路径转化为base路径，base_path的反向函数,前后均不包含斜杠
     * 
     * @param  string $path 路径
     * @return string 转换后路径
     */
    function path_base($path)
    {
        $path = Str::replaceFirst(base_path(), '', $path);
        $path = str_replace('\\', '/', $path);
        $path = trim($path, '/');
        return $path;
    }
}

if (! function_exists('trans_has')) {
    /**
     * 检查是否存在对应翻译
     *
     * @param  string  $key
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return bool
     */
    function trans_has($key, $locale = null, $fallback = true)
    {
        return app('translator')->has($key, $locale, $fallback);
    }
}

if (! function_exists('trans_find')) {
    /**
     * 翻译文件，可以从多个key中插座，没有找到翻译则结果返回空
     *
     * @param  string|array     $keys 如果是字符串，多个用||分割
     * @param  array            $replace
     * @param  string|null      $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function trans_find($keys, $replace = [], $locale = null)
    {
        if (is_string($keys)) {
            $keys = explode('||', $keys);
        }

        foreach ($keys as $key) {
            if (trans_has($key, $locale, false)) {
                return trans($key, $replace, $locale);
            }
        }

        return null;
    }
}

if (! function_exists('module')) {
    /**
     * 获取module
     *
     * @param  string|null  $name
     * @return mixed
     */
    function module($name = null)
    {
        if (is_null($name)) {
            return app('modules');
        }

        return app('modules')->findOrFail($name);
    }
}

if (! function_exists('preview')) {

    /**
     * 根据图片路径，预览站点内任意位置的图片
     * 
     * @param  string $path 图片路径 支持绝对路径和存储盘路径，public:uploads/abc.png
     * @param  int $width 图片宽度
     * @param  int $height 图片高度
     * @param  string $filter fit=适应 resize=缩放
     * @return string 预览地址
     */
    function preview($path, $width=null, $height=null, $filter='resize')
    {
        $preview = \App\Support\ImagePreview::file($path);

        // 如果是预览原图，因为原图一般都比较大，所以直接生成动态访问地址
        if (empty($width)) {
            return $preview->dynamic_url();
        }

        return $preview->width($width)->height($height)->filter($filter)->url();
    }
}


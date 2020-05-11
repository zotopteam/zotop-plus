<?php
namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;

class Format
{
    use Macroable;

    /**
     * 格式化路径，清理并转化分隔符，去掉结尾的分隔符，并将其转化为系统的风格符号,替换系统的一些变量
     *
     * @param string $path 路径字符串
     * @return string
     */
    public function path($path)
    {
        $path = preg_replace('#[/\\\\]+#', DIRECTORY_SEPARATOR, $path); //清理并转化
        $path = rtrim($path, DIRECTORY_SEPARATOR); //去掉结尾的分隔符号

        return $path;
    }

    /**
     * 格式化url，去除多余的斜杠，并转化为相对url或者绝对url
     *
     *
     * @param string $url
     * @return string
     */
    public static function url($url)
    {
        $url = str_replace("\\", "/", $url);
        $url = preg_replace("#(^|[^:])//+#", "\\1/", $url); //替换多余的斜线
        return $url;
    }
}

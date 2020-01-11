<?php
namespace App\Extend;

use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;

class Format
{
    use Macroable;
    
    /**
     * 格式化size为可读格式
     * 
     * @param  integer $bytes    size
     * @param  integer $decimals 小数位个数
     * @return string
     */
    public function size($bytes, $decimals = 2)
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

    /**
     * 格式化路径，清理并转化分隔符，去掉结尾的分隔符，并将其转化为系统的风格符号,替换系统的一些变量
     *
     * @param string $path 路径字符串
     * @return string
     */
    public function path($path)
    {
        $path = preg_replace('#[/\\\\]+#', DIRECTORY_SEPARATOR, $path); //清理并转化
        $path = rtrim($path,DIRECTORY_SEPARATOR); //去掉结尾的分隔符号

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

    /**
     * 格式化文本，一般用于格式化textarea的显示值
     * @param string $string
     * @return string
     */
    public function text($string)
    {
        $string = trim(str_replace(array('<p>', '</p>', '<br>', '<br/>','<br />'), '', $string));
        $string = '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", $string).'</p>';
        $string = str_replace(array('<p><br/></p>','<p></p>'), '', $string);
        $string = str_replace(' ', '&nbsp;', htmlspecialchars($string));

        return $string;
    }
    
}

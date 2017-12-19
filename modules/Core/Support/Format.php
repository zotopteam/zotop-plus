<?php
namespace Modules\Core\Support;

use Illuminate\Support\Carbon;

class Format
{
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

    /**
     * 格式化正则表达式
     *
     *
     * @param string $reg
     * @return string
     */
    public static function regex($regex)
    {
        $regex = str_replace(':any','.+',$regex);
        $regex = str_replace(':num','[0-9]+',$regex);

        return $regex;
    }

    /**
     * 判断字符串是否是时间戳
     * 
     * @param  mixed  $time 时间戳或者日期
     * @return boolean
     */
    public function isTimestamp($time)
    {
        if ((is_string($time) || is_numeric($time)) && strtotime(date('Y-m-d H:i:s',$time)) === $time) {
            return true;
        }
        return false;
    }

    /**
     * 日期时间格式化
     * 
     * @param  mixed  $time 时间戳或者日期
     * @param  string $format 时间日期格式： [datetime | date | time | Y-m-d H:i][ human] ，带human则显示友好时间，datetime human | Y-m-d H:i human
     * @param  string $timezone 时区
     * @param  int $inHuman 转换限定时间，单位秒，如：3600内的时间显示为友好时间
     * @return string
     */
    public function date($time, $format='datetime human', $timezone=null, $inHuman=null)
    {
        $timezone = $timezone ?? config('app.timezone');              
        $inHuman  = $inHuman ?? config('app.time_human');
        $isHuman  = false; 
        $formats  = \Filter::fire('date.formats', [
            'date'     => config('app.date_format'),
            'time'     => config('app.time_format'),
            'datetime' => config('app.date_format').' '.config('app.time_format'),
        ]);

        // 间隔一个空格
        if (ends_with($format, ' human') || starts_with($format, 'human ')) {
            $isHuman = true;
            $format  = trim(trim($format, 'human'));
        }

        if (isset($formats[$format])) {
            $format = $formats[$format];
        }

        // 格式化时间
        if ($time) {
            
            // TODO: 如果已经是Carbon实例

            // 如果不是时间戳，则转换为时间戳
            if (static::isTimestamp($time) == false) {
                $time = strtotime($time);
            }

            // 是否在human转换范围内
            if (time() > ($time + $inHuman)) {
                $isHuman = false;
            }

            $time = Carbon::createFromTimestamp($time);
    
            if ($isHuman) {
                return $time->diffForHumans();
            }

            return $time->format($format);        
        }

        return null;
    }
}

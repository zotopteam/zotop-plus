<?php

namespace App\Themes;


/**
 * 模板扩展，解析点格式的数组
 * 
 * @package App\Themes
 */
class DotArrayCompiler
{
    /**
     * 解析点语法数组
     * $a.b.c 转换为 $a['b']['c']
     * @$a.b.c 转换为 $a.b.c
     *
     * @param  string  $value
     * @return string
     */
    public function compile($value)
    {
        // 正则匹配所有 $a.b.c…… 或者 @$a.b.c…… 的字符 
        $pattern = '/(@)?\$[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+|(?R))+/s';

        // 替换回调
        $callback = function ($matches) {

            // 如果是以 @ 开头，直接返回去掉@后的字符串 @$a.b.c => $a.b.c
            if ($matches[1]) {
                return substr($matches[0], 1);
            }

            // $matches[0] => $a.b.c => $a['b']['c']
            $vars  = explode('.', $matches[0]);
            $first = array_shift($vars);
            return $first . '[\'' . implode('\'][\'', $vars) . '\']';
        };

        return preg_replace_callback($pattern, $callback, $value);
    }
}

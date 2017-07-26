<?php

namespace Modules\Core\Base;

use Illuminate\View\Compilers\BladeCompiler as LaravelBladeCompiler;

/**
 * 表单创建助手
 * 
 * @package App\Http
 */
class BladeCompiler extends LaravelBladeCompiler
{
    /**
     * All of the registered extensions.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * All of the available compiler functions.
     *
     * @var array
     */
    protected $compilers = [
        'DotArray',
        'Extensions',
        'Tags',
        'Statements',
        'Comments',
        'Echos',
    ];

    /**
     * Array of opening and closing tags for escaped echos.
     *
     * @var array
     */
    protected $simpleTags = ['{', '}'];

    
    /**
     * Execute the user defined extensions.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileTags($value)
    {
        return $value;
    }

    /**
     * 解析点数组语法
     *
     * @param  string  $value
     * @return string
     */
    protected function compileDotArray($value)
    {
        return $value;
    }

    /**
     * 将标签字符串转化为数组字符串
     * 
     * 
     * @param  string $str 标签字符串，所有参数都必须以半角（英文）双引号括起来，如： id="1" size="10" name="$name" placeholder="t('dddd')"
     * @return array
     */

    public function convertAttrs($str)
    {
       
        $attrs = $this->convertAttrsToArray($str);
        $attrs = $this->convertArrayToString($attrs);

        return $attrs;
    }

    /**
     * 将标签字符串转化为数组
     * 
     * 
     * @param  string $str 标签字符串，所有参数都必须以半角（英文）双引号括起来，如： id="1" size="10" name="$name" placeholder="t('dddd')"
     * @return array
     */
    public function convertAttrsToArray($str)
    {
        $attrs = array();

        preg_match_all("/\s+([a-z0-9_-]+)\s*\=\s*\"(.*?)\"/i", stripslashes($str), $matches, PREG_SET_ORDER);

        foreach ($matches as $v) {
            $attrs[$v[1]] = $v[2];
        }    

        return $attrs;        
    }

    /**
     * 将标签数组转化为数组字符串，支持 $变量 、function()函数 和 A::method() 静态方法 
     *
     * @param array $attrs 数组
     * @return code
     */
    public function convertArrayToString($attrs)
    {
        if (is_array($attrs)) {

            $str = '[';

            foreach ($attrs as $key => $val) {

                if (is_array($val)) {
                    
                    // 递归
                    $str .= "'$key'=>" . $this->convertArrayToString($val) . ",";

                } else {
                    
                    // TODO 属性中出现文字符合函数规则时会导致bug，比如：title="hello(hi,zotop)"，hello会被误以为是函数
                    if ( strpos($val, '$') === 0 OR preg_match('/[a-zA-Z\\_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(.*?\)/', $val) OR preg_match('/^\[.*\]$/', $val) ) {
                        $str .= "'$key'=>$val,";
                    } else {
                        $str .= "'$key'=>'" . addslashes($val) . "',";
                    }

                }
            }

            return trim($str, ',') . ']';
        }

        return '[]';
    }

}
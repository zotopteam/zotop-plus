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
        // 正则匹配所有{……$var.name……}或者{……$var.name.key……}的字符
        //数组允许使用的变量名称类型
        // return preg_replace_callback("/\{(.+?)\}/s", function($match) {
        //     $str = $match[1];
        //     if (preg_match_all('/\$(([a-zA-Z0-9_]+)((\.[a-zA-Z0-9_]+|(?R))+))/s', $str, $matches, PREG_OFFSET_CAPTURE) ){
                
        //         while ($matches[0]) {
        //             $match = array_pop($matches[0]);
        //             $match = $match[0]; // 取出 $aaa.bbb.ccc
        //             $vars  = explode('.', $match);
        //             $first = array_shift($vars);
        //             $array = $first . '[\'' . implode('\'][\'', $vars) . '\']';

        //             $str = str_replace($match, $array, $str);
        //         }        
        //     }
        //     return '{'.$str.'}';
        // }, $value);
        
        return $value;
    }

    /**
     * 将标签字符串转化为数组字符串
     * 
     * @param  string $str 标签字符串，所有参数都必须放在半角（英文）双引号内，支持字符串、null、bool、 $变量 、function()函数 和 A::method() 静态方法，如： id="1" size="10" name="$name" placeholder="t('dddd')"
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
     * @param  string $str 标签字符串，所有参数都必须放在半角（英文）双引号内， 如：id="1" type="image" name="$name" placeholder="t('dddd')"
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
     * 将convertAttrsToArray得到的标签数组转化为标准格式的数组字符串
     *
     * @param array $attrs 数组
     * @return code
     */
    public function convertArrayToString($attrs)
    {
        if (is_array($attrs)) {

            $str = '[';

            foreach ($attrs as $key => $val) {
                // 递归
                if (is_array($val)) {
                    $str .= "'$key'=>" . $this->convertArrayToString($val) . ",";
                } else {                    
                    $str .= "'$key'=>" . $this->convertStringToValue($val) . ",";
                }
            }

            return trim($str, ',') . ']';
        }

        return '[]';
    }

    /**
     * 转换参数值为数组真实类型
     * bool、 $变量 、function()函数 和 A::method() 静态方法 直接返回
     * 字符串加上单引号
     * 
     * @param  string  $str 标签参数
     * @return boolean
     */
    public function convertStringToValue($val)
    {
        // 如果是以$开头为变量  'key'=>$value
        if (strpos($val, '$') === 0) {
            return $val;
        }
        // 'null','true','false' 直接返回  'key'=>null,  'key'=>false,  'key'=>true 
        if (in_array(strtolower($val), ['null','true','false'])) {
            return $val;
        }
        // [……]，数组直接返回， 'key'=>[……],
        if (preg_match('/^\[.*\]$/', $val)) {
            return $val;
        }
        // test(……)，A::test(……) 函数或者方法直接返回 'key'=>test(……),'key'=>A::test(……),
        if (preg_match('/[a-zA-Z\\_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(.*?\)/', $val)) {
             return $val;
        }
        // 字符串类型加单引号后返回，TODO：可能有些数据需要处理 addslashes($val)
        return "'".$val."'";
    }
}

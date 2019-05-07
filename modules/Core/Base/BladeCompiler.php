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
     * 扩展tag标签
     * 
     * 1，函数：Blade::tag('block', 'block_tag');
     * 2，类方法：Blade::tag('block', 'Modules\Block\Hook\Listener@block_tag');
     * 3，匿名函数：Blade::tag('block', function($attrs) {……});
     * 
     * @param  string $name 标签名称
     * @param  mixed $callback 回调
     * @return null
     */
    public function tag($name, $callback)
    {
        $this->tags[$name] = $callback;
    }

    /**
     * Execute the user defined extensions.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileTags($value)
    {
        if ($this->tags) {

            // 获取全部标签正则
            $pattern = sprintf('/(@)?%s('.implode('|', array_keys($this->tags)).')(\s+[^}]+?)\s*%s/s', '{', '}');

            // 正则替换
            return preg_replace_callback($pattern, function($matches) {
                
                // 如果有@符号，@{block……} ，直接去掉@符号返回标签
                if ($matches[1]) {
                    return substr($matches[0], 1);
                }

                // 标签回调         
                $callback = 'Blade::tag_callback_'.$matches[2];
                $parameters = static::convertAttrs($matches[3]);

                // 返回解析
                return '<?php echo '.$callback .'('.$parameters.'); ?>';

            }, $value);
        }

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
        // 正则匹配所有 $var.name…… 或者 $var.name.key 的字符        
        if (preg_match_all('/\$(([a-zA-Z0-9_]+)((\.[a-zA-Z0-9_]+|(?R))+))/s', $value, $matches, PREG_OFFSET_CAPTURE)) {
            while ($matches[0]) {
                $match = array_pop($matches[0]);
                $match = $match[0];
                $vars  = explode('.', $match);
                $first = array_shift($vars);
                $array = $first . '[\'' . implode('\'][\'', $vars) . '\']';
                $value = str_replace($match, $array, $value);
            }        
        }

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


    public function __call($method, $args)
    {
        // tag_callback_block 函数
        if (starts_with($method, 'tag_callback')) {

            // 获取回调
            $method   = substr($method, 13);

            if (isset($this->tags[$method]) && $callback = $this->tags[$method]) {
                
                // 如果回调是类函数：字符串且包含@符号
                if (is_string($callback) && strpos($callback, '@')) {
                    $callback = explode('@', $callback);
                    $callback = array(app('\\' . $callback[0]), $callback[1]);
                }

                return call_user_func_array($callback, $args);
            }
        }
    }

}

<?php

namespace App\Support;

use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Contracts\Foundation\Application;

class Html
{
    use Macroable, ForwardsCalls {
        Macroable::__call as macroCall;
    }

    /**
     * app 实例
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * 创建一个表单实例
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 属性数组转化为属性字符串
     *
     * @param array $attributes
     * @return string
     */
    public function attributes(array $attributes)
    {
        $html = [];

        foreach ($attributes as $key => $value) {

            $convert = $this->convertAttribute($key, $value);

            if (!is_null($convert)) {
                $html[] = $convert;
            }
        }

        return count($html) > 0 ? implode(' ', $html) : '';
    }

    /**
     * 转换属性键/值为字符串
     * @param  sring $key 键名
     * @param  mixed $value 键值
     * @return string|null
     */
    public function convertAttribute($key, $value)
    {
        $key = strtolower($key);

        // [0=>'required'] 转换为 required
        if (is_numeric($key)) {
            return $value;
        }

        // ['required'=>true] 转换为 required，但是不转换 ['value'=>true]
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : null;
        }

        // ['class'=>['aaa','bbb']] 转换为 class="aaa bbb"
        if (is_array($value) && $key === 'class') {
            return $key . '="' . implode(' ', $value) . '"';
        }

        // ['name'=>’aaa‘] 转化为 name='aaa'
        if (!is_null($value)) {
            return $key . '="' . e($value, false) . '"';
        }

        return null;
    }

    /**
     * 文本转html，一般用于格式化textarea的显示值
     * 
     * @param string $string
     * @return string
     */
    public function text($string)
    {
        $string = trim(str_replace(array('<p>', '</p>', '<br>', '<br/>', '<br />'), '', $string));
        $string = '<p>' . preg_replace("/([\n]{1,})/i", "</p>\n<p>", $string) . '</p>';
        $string = str_replace(array('<p><br/></p>', '<p></p>'), '', $string);
        $string = str_replace(' ', '&nbsp;', htmlspecialchars($string));

        return $string;
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Illuminate\Contracts\View\View|mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        static::throwBadMethodCallException($method);
    }
}

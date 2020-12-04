<?php

namespace App\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

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
        return new Attribute($attributes);
    }
    
    /**
     * 文本转html，一般用于格式化textarea的显示值
     *
     * @param string $string
     * @return string
     */
    public function text(string $string)
    {
        $string = trim(str_replace(['<p>', '</p>', '<br>', '<br/>', '<br />'], '', $string));
        $string = '<p>' . preg_replace("/([\n]{1,})/i", "</p>\n<p>", $string) . '</p>';
        $string = str_replace(['<p><br/></p>', '<p></p>'], '', $string);
        $string = str_replace(' ', '&nbsp;', htmlspecialchars($string));

        return $string;
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return \Illuminate\Contracts\View\View|mixed|void
     *
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        static::throwBadMethodCallException($method);
    }
}

<?php

namespace App\Support;

use App\Support\Form\Traits\Controlable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class Form
{
    use Macroable, Controlable, ForwardsCalls {
        Macroable::__call as macroCall;
    }

    /**
     * app 实例
     *
     * @var Html
     */
    protected $app;


    /**
     * 表单绑定的数组或者实例
     *
     * @var Html
     */
    protected $bind = null;

    /**
     * 表单追加项
     *
     * @var array
     */
    protected $append = [];

    /**
     * 表单默认的类名
     *
     * @var string
     */
    public static $defaultClass = 'form';

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
     * 生成 <form ……> 标签
     *
     * @param array $options
     * @return string
     */
    public function open(array $options = [])
    {
        // 绑定模型的数组或者实例
        $this->bind = Arr::pull($options, 'bind', null);

        $attributes = new Attribute([
            'method'         => $this->formMethod($options),
            'action'         => $this->formAction($options),
            'class'          => $this->fromClass($options),
            'enctype'        => $this->formEnctype($options),
            'accept-charset' => 'UTF-8',
        ]);

        return $this->toHtmlString(
            '<form ' . $attributes->merge($options) . '>' . PHP_EOL . implode(PHP_EOL, $this->append)
        );
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close()
    {
        $this->append = [];
        $this->bind = null;

        return $this->toHtmlString('</form>');
    }

    /**
     * 表单方法
     *
     * @param array $options
     * @return string
     */
    protected function formMethod(&$options)
    {
        $method = Arr::pull($options, 'method', 'post');
        $method = strtoupper($method);

        // 如果是['DELETE', 'PATCH', 'PUT']方法之一，附加为表单隐藏域
        if (in_array($method, ['DELETE', 'PATCH', 'PUT'])) {
            $this->append[] = $this->callControl('hidden', ['name' => '_method', 'value' => $method]);
        }

        // 追加token
        if ($method !== 'GET') {

            $token = $this->app['session.store']->token();
            $token = !empty($token) ? $token : $this->app['session']->token();

            $this->append[] = $this->callControl('hidden', ['name' => '_token', 'value' => $token]);
            return 'POST';
        }

        return 'GET';
    }

    /**
     * 表单方法
     *
     * @param array $options
     * @return string
     */
    protected function formAction(&$options)
    {
        $keys = ['url', 'route', 'action'];

        if ($attributes = Arr::only($options, $keys)) {

            // 从属性中删除'url', 'route', 'action'
            Arr::forget($options, $keys);

            foreach ($attributes as $method => $parameter) {
                // ['route.name', 'parameter1', 'parameter2'……]
                if (is_array($parameter)) {
                    return call_user_func_array($method, [$parameter[0], array_slice($parameter, 1)]);
                }
                // ‘route.name’
                if (is_string($parameter)) {
                    return call_user_func_array($method, [$parameter]);
                }
            }
        }

        return $this->app['url']->current();
    }

    /**
     * 表单方法
     *
     * @param array $options
     * @return string
     */
    protected function formEnctype(&$options)
    {
        $keys = ['files', 'enctype'];

        if (Arr::only($options, $keys)) {
            Arr::forget($options, $keys);
            return 'multipart/form-data';
        }

        return null;
    }

    /**
     * 表单类名
     *
     * @param $options
     * @return array|\ArrayAccess|mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function fromClass(&$options)
    {
        return Arr::pull($options, 'class', static::$defaultClass);
    }

    /**
     * 统一字段的调用方式
     *
     * @param array $attributes
     * @return string
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function field(array $attributes = [])
    {
        // 获取类型
        $type = $this->findControl(Arr::pull($attributes, 'type'));

        return $this->callControl($type, $attributes);
    }

    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method
     * @param array $parameters
     * @return \Illuminate\Contracts\View\View|mixed|void
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if (static::hasControl($method)) {
            return $this->callControl($method, Arr::wrap($parameters[0] ?? []));
        }

        static::throwBadMethodCallException($method);
    }
}

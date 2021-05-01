<?php

namespace Zotop\Support\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

abstract class Control
{
    use Macroable;

    /**
     * @var string
     */
    public static $defaultClass = 'form-control';

    /**
     * 数据
     *
     * @var array
     */
    protected $data = [];

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $key 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     *
     * @access protected
     * @param string $key 模板显示变量
     * @return mixed
     */
    public function __get(string $key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * 检测模板变量是否被设定
     *
     * @access protected
     * @param string $key 模板显示变量
     * @return bool
     */
    public function __isset(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * 删除模板变量
     *
     * @param $key
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function __unset($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method
     * @param array $parameters
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if (property_exists(static::class, $method)) {
            static::$$method = $parameters[0] ?? null;
        }
    }

    /**
     * 默认样式
     *
     * @return string
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function getDefaultClass()
    {
        return static::$defaultClass . ' ' . static::$defaultClass . '-' . $this->type;
    }

    /**
     * 传入参数, 支持链式
     *
     * @param string|array $key 参数名
     * @param mixed $value 参数值
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        }

        if (is_string($key) && $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * 获取给定视图的内容
     *
     * @param string|null $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        // 默认模板
        $view = $view ?: "controls.{$this->controlName}";

        // 转换模板数据
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        // 合并模板数据
        $data = array_merge($this->data, $data);

        // 生成 view
        return view($view, $data, $mergeData)
            ->with('control', $this); // 允许在模板中使用 control 中定义的public方法
    }

    /**
     * 将字符串转换为可序列化的HTML对象
     *
     * @param string $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString(string $html)
    {
        return new HtmlString($html);
    }

    /**
     * 初始化
     *
     * @return $this
     * @author Chen Lei
     * @date 2020-12-07
     */
    public function initialize()
    {
        $this->boot();

        $class = static::class;

        // 启动子类
        $bootSelf = 'boot' . class_basename($class);

        // 启动类型
        $bootType = 'boot' . Str::studly($this->type);

        // 检查方法是否存在，存在则调用
        foreach (array_unique([$bootSelf, $bootType]) as $method) {
            if (method_exists($class, $method)) {
                $this->$method();
            }
        }

        return $this;
    }

    /**
     * 启动
     *
     * @author Chen Lei
     * @date 2020-12-07
     */
    public function boot()
    {
        $this->attributes->addClass($this->getDefaultClass(), true);
    }

    /**
     * 渲染控件，获取控件的视图 / 内容
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    abstract public function render();
}

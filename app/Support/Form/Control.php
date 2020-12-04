<?php

namespace App\Support\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;

abstract class Control
{
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
        return view($view, $data, $mergeData);
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
     * 渲染控件，获取控件的视图 / 内容
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    abstract public function render();
}

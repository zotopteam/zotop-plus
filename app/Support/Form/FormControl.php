<?php

namespace App\Support\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

abstract class FormControl
{
    /**
     * 属性缓存
     *
     * @var array
     */
    protected static $propertyCache = [];

    /**
     * 控件属性
     *
     * @var array
     */
    public $attributes = [];

    /**
     * 控件名称
     *
     * @var string
     */
    public $controlName;

    /**
     * 控件属性赋值
     *
     * @param array $attributes
     * @return \App\Support\Form\FormControl
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function withName(string $name)
    {
        $this->controlName = $name;

        return $this;
    }

    /**
     * 控件属性赋值
     *
     * @param array $attributes
     * @return \App\Support\Form\FormControl
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function withAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * 获取给定视图的内容
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function view(string $view, $data = [], $mergeData = [])
    {
        // 转换模板数据
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        // 合并模板数据
        $data = array_merge($this->extractPublicProperties(), $data);

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
     * 提取控件的公共属性
     *
     * @return array
     */
    protected function extractPublicProperties()
    {
        $class = get_class($this);

        if (!isset(static::$propertyCache[$class])) {
            $reflection = new ReflectionClass($this);

            static::$propertyCache[$class] = collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
                ->reject(function (ReflectionProperty $property) {
                    return $property->isStatic();
                })
                ->reject(function (ReflectionProperty $property) {
                    return $this->shouldIgnore($property->getName());
                })
                ->map(function (ReflectionProperty $property) {
                    return $property->getName();
                })->all();
        }

        $values = [];

        foreach (static::$propertyCache[$class] as $property) {
            $values[$property] = $this->{$property};
        }

        return $values;
    }

    /**
     * 应该忽略的属性
     *
     * @param string $name
     * @return bool
     */
    protected function shouldIgnore($name)
    {
        return Str::startsWith($name, '__');
    }

    /**
     * 渲染控件，获取控件的视图 / 内容
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|\Closure|string
     */
    abstract public function render();
}

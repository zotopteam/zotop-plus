<?php

namespace Zotop\Support\Form\Traits;

use Zotop\Support\Attribute;
use Zotop\Support\Form\Control;
use Zotop\Support\Form\Controls\Button;
use Zotop\Support\Form\Controls\Checkable;
use Zotop\Support\Form\Controls\Input;
use Zotop\Support\Form\Controls\Select;
use Zotop\Support\Form\Controls\Textarea;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

trait Controlable
{
    /**
     * 字段容器
     * input : 'hidden', 'text', 'number', 'password', 'email', 'url', 'tel', 'date', 'datetime', 'time', 'month', 'week', 'range', 'file', 'color', 'search'
     * button: 'button', 'submit', 'save', 'reset'
     * other: 'textarea', 'checkbox', 'radio'
     *
     * @var array
     */
    protected $controls = [
        'hidden'   => Input::class,
        'text'     => Input::class,
        'number'   => Input::class,
        'password' => Input::class,
        'email'    => Input::class,
        'url'      => Input::class,
        'tel'      => Input::class,
        'date'     => Input::class,
        'datetime' => Input::class,
        'time'     => Input::class,
        'month'    => Input::class,
        'week'     => Input::class,
        'range'    => Input::class,
        'file'     => Input::class,
        'color'    => Input::class,
        'search'   => Input::class,
        'textarea' => Textarea::class,
        'button'   => Button::class,
        'submit'   => Button::class,
        'save'     => Button::class,
        'reset'    => Button::class,
        'select'   => Select::class,
        'radio'    => Checkable::class,
        'checkbox' => Checkable::class,
    ];

    /**
     * 格式化控件名称，统一为小写，中线分隔
     *
     * @param string $type
     * @return string
     * @author Chen Lei
     * @date 2020-12-11
     */
    public function typeNameFormat(string $type)
    {
        // 控件名称转换为小写，替换中控件名称中的 _ . : / 为 -
        return str_replace(['_', '.', ':', '/'], ['-', '-', '-', '-'], strtolower($type));
    }

    /**
     * 定义一个字段
     *
     * @param string|array $type
     * @param string|\Closure $callback
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function control($type, $callback)
    {
        if (is_string($type) && Str::contains($type, '\\')) {
            [$callback, $type] = [$type, $callback];
        }

        foreach ((array)$type as $key) {
            $this->controls[$this->typeNameFormat($key)] = $callback;
        }
    }

    /**
     * 字段是否存在
     *
     * @param string $type
     * @return bool
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function hasControl(string $type)
    {
        return isset($this->controls[$this->typeNameFormat($type)]);
    }

    /**
     * 依次查找类型，直到找到，找不到返回text
     *
     * @param array $types 类型
     * @return string
     */
    protected function findControl(...$types)
    {
        foreach ($types as $type) {
            if ($this->hasControl($type)) {
                return $type;
            }
        }

        return 'text';
    }

    /**
     * 全部控件
     *
     * @return array|string[]
     * @author Chen Lei
     * @date 2020-12-22
     */
    public function controls()
    {
        return $this->controls;
    }

    /**
     * 调用字段
     *
     * @param string $type
     * @param array $attributes
     * @return string
     * @throws \ReflectionException
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function callControl(string $type, array $attributes)
    {
        // 格式化控件类型
        $type = $this->typeNameFormat($type);

        // 获取回调
        $callback = $this->controls[$type];

        // 原始标签
        $originalAttributes = $attributes;

        // 完善$attributes，补充 id 和 value
        $attributes = $this->completeAttributes($attributes);

        // 闭包方式
        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($this, static::class);
            return $callback(
                new Attribute($attributes),
                new Attribute($originalAttributes)
            );
        }

        // 类方式
        if (class_exists($callback) && is_subclass_of($callback, Control::class) && !(new ReflectionClass($callback))->isAbstract()) {

            // 分解为控件初始化参数和标签参数
            [$data, $attributes] = $this->partitionDataAndAttributes($callback, $attributes);

            // 键名转换小驼峰格式
            $data = collect($data)->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            });

            return app($callback, $data->all())
                ->with('form', $this)
                ->with('type', $type)
                ->with('attributes', new Attribute($attributes))
                ->with('originalAttributes', new Attribute($originalAttributes))
                ->with('bind', $this->bind)
                ->with('bindValue', $this->getBind($attributes['name'] ?? null))
                ->initialize()
                ->render();
        }

        return '';
    }

    /**
     * Partition the data and extra attributes from the given array of attributes.
     *
     * @param string $class
     * @param array $attributes
     * @return array
     * @throws \ReflectionException
     */
    protected function partitionDataAndAttributes($class, array $attributes)
    {
        $constructor = (new ReflectionClass($class))->getConstructor();

        $parameterNames = $constructor
            ? collect($constructor->getParameters())->map->getName()->all()
            : [];

        return collect($attributes)->partition(function ($value, $key) use ($parameterNames) {
            return in_array(Str::camel($key), $parameterNames);
        })->transform(function ($item) {
            return $item->toArray();
        })->all();
    }

    /**
     * 完善标签
     *
     * @param array $attributes
     * @return array
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function completeAttributes(array $attributes)
    {
        $attributes['id'] = $attributes['id'] ?? $this->convertNameToId($attributes);
        $attributes['value'] = $attributes['value'] ?? $this->getBind($attributes['name'] ?? null);

        return $attributes;
    }

    /**
     * 通过name转为为id
     *
     * @param array $attributes
     * @return string
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function convertNameToId(array $attributes)
    {
        // 格式化name为id
        return str_replace(['.', '[]', '[', ']'], ['-', '', '-', ''], Arr::get($attributes, 'name', ''));
    }

    /**
     * 获取绑定值
     *
     * @param string|null $name
     * @return array|mixed
     * @author Chen Lei
     * @date 2020-12-18
     */
    public function getBind($name, $default = null)
    {
        if ($name) {

            // 将数组名称转为点语法 test[aaa] 转为 test.aaa
            $key = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

            // 从闪存数据中取值
            $old = $this->app['request']->old($key);

            if (!is_null($old)) {
                return $old;
            }

            // 从绑定数据中取值
            return data_get($this->bind, $key);
        }

        return $default;
    }
}

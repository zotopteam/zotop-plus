<?php

namespace App\Support\Form\Traits;

use App\Support\Attribute;
use App\Support\Form\Control;
use App\Support\Form\Controls\Button;
use App\Support\Form\Controls\Checkable;
use App\Support\Form\Controls\Input;
use App\Support\Form\Controls\Select;
use App\Support\Form\Controls\Textarea;
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
     * 定义一个字段
     *
     * @param string|array $type
     * @param string|\Closure $callback
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function control($type, $callback)
    {
        if (!is_null($type) && Str::contains($type, '\\')) {
            [$callback, $type] = [$type, $callback];
        }

        foreach ((array)$type as $key) {
            $this->controls[strtolower($key)] = $callback;
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
        return isset($this->controls[strtolower($type)]);
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
        // 获取回调
        $callback = $this->controls[strtolower($type)];

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

            // 分解为控件的
            [$data, $attributes] = $this->partitionDataAndAttributes($callback, $attributes);

            return app($callback, $data)
                ->with('form', $this)
                ->with('type', $type)
                ->with('attributes', new Attribute($attributes))
                ->with('originalAttributes', new Attribute($originalAttributes))
                ->with('bind', $this->bind)
                ->with('bindValue', $this->getBindValue($attributes))
                ->beforeRender()
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
        $attributes['value'] = $attributes['value'] ?? $this->getBindValue($attributes);

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
     * 从bind数据中获取value
     *
     * @param array $attributes
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getBindValue(array $attributes)
    {
        // 从绑定值中获取value
        if ($name = Arr::get($attributes, 'name')) {

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

        return null;
    }
}

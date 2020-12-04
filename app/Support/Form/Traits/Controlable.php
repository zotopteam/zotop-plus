<?php

namespace App\Support\Form\Traits;

use App\Support\Attribute;
use App\Support\Form\Control;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

trait Controlable
{
    /**
     * 字段容器
     *
     * @var array
     */
    protected $controls = [];

    /**
     * 定义一个字段
     *
     * @param string $name
     * @param string|\Closure $callback
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function control(string $name, $callback)
    {
        $this->controls[strtolower($name)] = $callback;
    }

    /**
     * 字段是否存在
     *
     * @param string $name
     * @return bool
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function hasControl(string $name)
    {
        return isset($this->controls[strtolower($name)]);
    }

    /**
     * 调用字段
     *
     * @param string $name
     * @param array $parameters
     * @return string
     * @author Chen Lei
     * @date 2020-12-02
     */
    public function callControl(string $name, array $attributes)
    {
        $callback = $this->controls[strtolower($name)];

        Arr::forget($attributes, 'type');

        // 闭包方式
        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($this, static::class);
            return $callback($attributes);
        }

        // 类方式
        if (class_exists($callback) && is_subclass_of($callback, Control::class) && !(new ReflectionClass($callback))->isAbstract()) {

            // 分解为控件的
            [$data, $attributes] = $this->partitionDataAndAttributes($callback, $attributes);

            return app($callback, $data)
                ->with('controlName', $name)
                ->with('attributes', new Attribute($attributes))
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
}

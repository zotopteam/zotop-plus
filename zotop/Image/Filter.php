<?php

namespace Zotop\Image;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

abstract class Filter implements FilterInterface
{
    /**
     * image filters 容器
     *
     * @var array
     */
    public static $filters = [];

    /**
     * 设置filter
     *
     * @param mixed $name 滤器名称
     * @param string $class 类名
     */
    public static function set($name, string $class)
    {
        $name = strtolower($name);

        // 如果类存在，则设置
        if (class_exists($class)) {
            static::$filters[$name] = $class;
        }
    }

    /**
     * 获取filter实例
     *
     * @param string $name 滤器名称
     * @param array|null $parameters 滤器参数
     * @return Filter
     */
    public static function get(string $name, $parameters = null)
    {
        $name = strtolower($name);

        // 支持快捷语法：resize:300-300 fit:300-200
        if (strpos($name, ':')) {
            [$name, $parameters] = explode(':', $name);
        }

        if (isset(static::$filters[$name])) {

            // 获取滤镜类
            $class = static::$filters[$name];

            // 如果参数为数组, 实例化并传入参数
            if (is_array($parameters)) {
                $filter = new $class();
                foreach ($parameters as $key => $value) {
                    $filter->{$key} = $value;
                }
                return $filter;
            }

            // 如果参数为字符串或者null，则直接传入
            return new $class($parameters);
        }

        return null;
    }

    /**
     * 快速应用滤镜
     *
     * @param mixed $image
     * @param string $filter 注册的滤镜名称
     * @param mixed $parameter 滤镜参数，从滤镜__construct传入
     * @return mixed
     */
    public static function apply($image, string $filter, $parameter = null)
    {
        // 获取滤器
        if ($filter = static::get($filter, $parameter)) {
            $image->filter($filter);
        }

        return $image;
    }

    /**
     * Applies filter effects to given image
     *
     * @param \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        return $image;
    }

    /**
     * 动态方法
     *
     * @param string $method
     * @param array $parameters
     * @return $this
     *
     * @throws \Exception
     */
    public function __call(string $method, array $parameters)
    {
        // 如果当前类存在属性，则直接用属性名方法赋值给属性
        if ($parameters && property_exists($this, $method)) {
            $this->$method = reset($parameters);
            return $this;
        }

        throw new \Exception('Call to undefined method ' . get_class($this) . "::{$method}()");
    }
}

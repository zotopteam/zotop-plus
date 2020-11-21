<?php

namespace App\Support;

abstract class Enum
{

    /**
     * 获取全部定义的nums
     *
     * @return array
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function all()
    {
        $reflect = new \ReflectionClass(static::class);
        $enums = $reflect->getConstants();

        return $enums;
    }

    /**
     * 转换为数组，等同于all
     *
     * @return array
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function toArray()
    {
        return static::all();
    }

    /**
     * 转换为字符串
     *
     * @param string $delimiter
     * @return string
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function toString($delimiter = ',')
    {
        return implode($delimiter, static::all());
    }

    /**
     * 转换为集合
     *
     * @return \Illuminate\Support\Collection
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function toCollection()
    {
        return collect(static::all());
    }

    /**
     * 检查当前enum中是否含有某个值
     *
     * @param $enum
     * @return bool
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function has($enum)
    {
        return in_array($enum, static::all());
    }

    /**
     * 返回所有定义的key值
     *
     * @param int $case CASE_LOWER|CASE_UPPER
     * @return array
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function keys($case = null)
    {
        if (isset($case)) {
            return array_keys(array_change_key_case(static::all(), $case));
        }

        return array_keys(static::all());
    }

    /**
     * 返回所有定义的values值
     *
     * @return array
     * @author Chen Lei
     * @date 2020-11-21
     */
    protected function values()
    {
        return array_values(static::all());
    }

    /**
     * Handle dynamic static method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}

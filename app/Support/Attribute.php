<?php

namespace App\Support;

use ArrayAccess;
use ArrayIterator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;

class Attribute implements ArrayAccess, Htmlable, IteratorAggregate
{
    use Macroable;

    /**
     * The raw array of attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new component attribute bag instance.
     *
     * @param mixed $attributes
     * @return void
     */
    public function __construct($attributes)
    {
        $this->attributes = Arr::wrap($attributes);
    }

    /**
     * Get the first attribute's value.
     *
     * @param mixed $default
     * @return mixed
     */
    public function first($default = null)
    {
        return $this->getIterator()->current() ?? value($default);
    }

    /**
     * check attribute array has key.
     *
     * @param string|string[] $key
     * @return mixed
     */
    public function has($key)
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * 判断数组中是否存在给定集合中的任一值作为键
     *
     * @param mixed ...$keys
     * @return bool
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function hasAny(...$keys)
    {
        foreach ($keys as $key) {
            if (static::has($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 判断数组中是否存在给定集合中的所有键
     *
     * @param mixed ...$keys
     * @return bool
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function hasMany(...$keys)
    {
        foreach ($keys as $key) {
            if (!static::has($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get a given attribute from the attribute array.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * set key/value to attributes.
     *
     * @param string $key
     * @param mixed $value
     * @param bool $overwrite
     * @return static
     */
    public function set(string $key, $value, $overwrite = true)
    {
        // 如果覆盖 或者 键名不存在，则设置值
        if ($overwrite == true || !$this->has($key)) {
            Arr::set($this->attributes, $key, $value);
        }

        return $this;
    }

    /**
     * 从$keys中依次检索key是否存在，存在就推出该值
     *
     * @param array $keys
     * @param mixed $default
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function find(array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($this->attributes[$key])) {
                return $this->attributes[$key];
            }
        }

        return value($default);
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param string|array $key
     * @param mixed $default
     * @return string|array
     */
    public function pull($key, $default = null)
    {
        if (is_string($key)) {
            $value = $this->get($key, $default);
        }

        if (is_array($key)) {
            $value = $this->only($key, $default ?? []);
        }

        $this->forget($key);

        return $value;
    }

    /**
     * 从$keys中依次检索key是否存在，存在就推出该值
     *
     * @param array $keys
     * @param mixed $default
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function pullFirst(array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($this->attributes[$key])) {
                return tap($this->attributes[$key], function ($value) use ($key) {
                    unset($this->attributes[$key]);
                });
            }
        }

        return value($default);
    }

    /**
     * Remove one or many array items from attributes.
     *
     * @param array|string $keys
     * @return $this
     */
    public function forget($keys)
    {
        foreach ((array)$keys as $key) {
            if (isset($this->attributes[$key])) {
                unset($this->attributes[$key]);
            }
        }

        return $this;
    }

    /**
     * Only include the given attribute from the attribute array.
     *
     * @param string|array $keys
     * @param array $default
     * @return static
     */
    public function only($keys, $default = [])
    {
        if (is_null($keys)) {
            return $this;
        }

        $values = Arr::only($this->attributes, Arr::wrap($keys));

        if (empty($values)) {
            $values = Arr::wrap(value($default));
        }

        return new static($values);
    }

    /**
     * Exclude the given attribute from the attribute array.
     *
     * @param string|array $keys
     * @param array $default
     * @return static
     */
    public function except($keys, $default = [])
    {
        if (is_null($keys)) {
            return $this;
        }

        $values = Arr::except($this->attributes, Arr::wrap($keys));

        if (empty($values)) {
            $values = Arr::wrap(value($default));
        }

        return new static($values);
    }

    /**
     * Filter the attributes, returning a bag of attributes that pass the filter.
     *
     * @param callable $callback
     * @return static
     */
    public function filter($callback)
    {
        return new static($this->toCollection()->filter($callback)->all());
    }

    /**
     * Merge attributes
     *
     * @param array $attributes
     * @param bool $overwrite 是否允许覆盖
     * @return $this
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function merge(array $attributes, $overwrite = true)
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $value, $overwrite);
        }

        return $this;
    }

    /**
     * 转换class为数组
     *
     * @param array|string $class
     * @return array|false|string|string[]
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function convertClassToArray($class)
    {
        if (is_array($class)) {
            return $class;
        }

        if (is_string($class)) {
            return array_filter(explode(' ', $class));
        }

        return [];
    }

    /**
     * 添加 class
     *
     * @param mixed $addClass 添加的class
     * @param boolean $prepend 是否前置
     * @return $this
     */
    public function addClass($addClass, $prepend = false)
    {
        $class = $this->convertClassToArray($this->attributes['class'] ?? []);

        $addClass = $this->convertClassToArray($addClass);

        // 添加的class是前置，还是后置
        if ($prepend) {
            $class = array_merge($addClass, $class);
        } else {
            $class = array_merge($class, $addClass);
        }

        $this->attributes['class'] = implode(' ', array_values(array_unique($class)));

        return $this;
    }

    /**
     * 删除 class
     *
     * @param null|string|array $removeClass
     * @return $this
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function removeClass($removeClass = null)
    {
        if (empty($removeClass)) {
            return $this->forget('class');
        }

        $class = array_diff(
            $this->convertClassToArray($this->attributes['class'] ?? []),
            $this->convertClassToArray($removeClass),
        );

        if (empty($class)) {
            return $this->forget('class');
        }

        $this->attributes['class'] = implode(' ', array_values(array_unique($class)));

        return $this;
    }


    /**
     * add data-x to attributes
     *
     * @param string|array $key name part of data-name, Exclude data-
     * @param string|null $value
     * @return $this
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function addData($key, $value = null)
    {
        $data = is_array($key) ? $key : [$key => $value];

        foreach ($data as $key => $value) {
            $this->attributes["data-{$key}"] = $value;
        }

        return $this;
    }

    /**
     * remove data-x from attributes
     *
     * @param string|array $keys name part of data-name, Exclude data-
     * @return $this
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function removeData($keys)
    {
        foreach ((array)$keys as $key) {
            $key = "data-{$key}";
            if (isset($this->attributes[$key])) {
                unset($this->attributes[$key]);
            }
        }

        return $this;
    }

    /**
     * Get all of the raw attributes.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Get all of the raw attributes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection()
    {
        return collect($this->attributes);
    }

    /**
     * 转换成字符串
     *
     * @return string
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function toString()
    {
        $string = '';

        foreach ($this->attributes as $key => $value) {

            try {
                if ($value === false || is_null($value)) {
                    continue;
                }

                if ($value === true) {
                    $value = $key;
                }

                if (is_array($value)) {
                    $value = var_export_pretty($value, true);
                }

                $string .= ' ' . $key . '="' . str_replace('"', '\\"', trim($value)) . '"';
            } catch (\Throwable $th) {
                debug($th->getMessage() . ' | ' . $th->getFile() . ' | ' . $th->getLine(), $this->attributes, $value);
                continue;
            }

        }

        return trim($string);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->toString();
    }

    /**
     * Determine if the given offset exists.
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Get the value at the given offset.
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset] ?? null;
    }

    /**
     * Set the value at a given offset.
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Remove the value at the given offset.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attributes);
    }

    /**
     * Implode the attributes into a single HTML ready string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}

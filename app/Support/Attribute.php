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
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
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
     * Get a given attribute from the attribute array.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? value($default);
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);

        $this->forget($key);

        return $value;
    }

    /**
     * Remove one or many array items from attributes.
     *
     * @param array $array
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
     * @param mixed|array $keys
     * @return static
     */
    public function only($keys)
    {
        if (is_null($keys)) {
            return $this;
        }

        $keys = Arr::wrap($keys);

        $values = Arr::only($this->attributes, $keys);

        return new static($values);
    }

    /**
     * Exclude the given attribute from the attribute array.
     *
     * @param mixed|array $keys
     * @return static
     */
    public function except($keys)
    {
        if (is_null($keys)) {
            return $this;
        }

        $keys = Arr::wrap($keys);

        $values = Arr::except($this->attributes, $keys);

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
     * @return $this
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function merge(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * 转换class为数组
     *
     * @param array|string $class
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
     * @param array $attributes 属性
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
     * add data to attributes
     *
     * @param string|array $key
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
     * remove data from attributes
     *
     * @param string|array $keys
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
            if ($value === false || is_null($value)) {
                continue;
            }

            if ($value === true) {
                $value = $key;
            }

            $string .= ' ' . $key . '="' . str_replace('"', '\\"', trim($value)) . '"';
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

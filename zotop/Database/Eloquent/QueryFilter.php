<?php

namespace Zotop\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * QueryFilter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 全局滤器
     *
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function boot()
    {
        // 需要在子类中实现
    }

    /**
     * 应用滤器
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function apply(Builder $query)
    {
        $this->query = $query;

        // Filter global methods
        $this->boot();

        foreach ($this->getFilters() as $name => $value) {
            // 如果值为空，则不执行
            if (!$this->isFilterValue($value)) {
                continue;
            }

            // 如果能找到滤器方法，则调用滤器方法
            if ($method = $this->guessFilterMethod($name)) {
                call_user_func_array([$this, $method], array_filter([$value]));
            }
        }

        return $this->query;
    }

    /**
     * 获取全部的请求参数作为滤器字段
     *
     * @return array
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function getFilters()
    {
        return $this->request->all();
    }

    /**
     * 获取可能的滤器方法名称
     *
     * @param string $name
     * @return mixed|string|null
     * @author Chen Lei
     * @date 2020-11-23
     */
    private function guessFilterMethod(string $name)
    {
        // $name=title 方法名称为title
        // $name=category_id 可能的方法名称为 category_id，categoryId
        foreach (array_unique([$name, Str::camel($name)]) as $method) {
            if (method_exists($this, $method) && !method_exists(self::class, $method)) {
                return $method;
            }
        }

        return null;
    }

    /**
     * 判断滤器是否拥有值
     *
     * @param mixed $value
     * @return bool
     * @author Chen Lei
     * @date 2020-11-23
     */
    private function isFilterValue($value)
    {
        return $value !== '' && $value !== null && !(is_array($value) && empty($value));
    }
}

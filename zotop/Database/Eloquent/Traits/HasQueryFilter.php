<?php

namespace Zotop\Database\Eloquent\Traits;

use Zotop\Database\Eloquent\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait HasQueryFilter
{
    /**
     * 查询滤器
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Zotop\Database\Eloquent\QueryFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function scopeFilter(Builder $query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}

<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'block_category';
    protected $fillable = ["name","description"];

    /**
     * 查询排序
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSorted($query)
    {
        return $query->orderby('sort', 'asc')->orderby('id', 'asc');
    }    
}

<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $table = 'block_category';
    protected $fillable = ["name","description"];

    /**
     * 全局作用域
     * 
     * @return null
     */
    protected static function boot()
    {
        parent::boot();

        // sort
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderby('sort', 'asc')->orderby('id', 'asc');
        });
    }
 
}

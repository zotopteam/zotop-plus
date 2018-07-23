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

        // sort 排序全局作用域
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderby('sort', 'asc')->orderby('id', 'asc');
        });
    }

    /**
     * 和block的关联
     * @return hasMany
     */
    public function blocks() {
        return $this->hasMany(Block::class, 'category_id', 'id');
    } 
}

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

        // 为安全考虑，禁止删除非空的模型
        static::deleting(function($category) {

            // 如果已经有数据，不能删除
            if ($category->block()->count()) {
                abort(403, trans('block::category.delete.hasblock'));
            }                   
        });
    }

    /**
     * 和block的关联
     * @return hasMany
     */
    public function block() {
        return $this->hasMany(Block::class, 'category_id', 'id');
    }

    /**
     * 排序
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query)
    {
        return $query->orderby('sort', 'asc')->orderby('id', 'asc');
    }    
}

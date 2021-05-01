<?php

namespace Modules\Block\Models;

use Zotop\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'block_category';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ["name", "description"];

    /**
     * booted
     *
     * @return void
     */
    protected static function booted()
    {
        // 为安全考虑，禁止删除非空的模型
        static::deleting(function ($category) {
            // 如果已经有数据，不能删除
            if ($category->block()->count()) {
                abort(403, trans('block::category.delete.hasblock'));
            }
        });
    }

    /**
     * 和block的关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Chen Lei
     * @date 2020-11-28
     */
    public function block()
    {
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

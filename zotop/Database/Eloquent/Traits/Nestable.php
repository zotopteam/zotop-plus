<?php

namespace Zotop\Database\Eloquent\Traits;

use Zotop\Database\Eloquent\Traits\Exceptions\NestableDeleteException;
use Zotop\Database\Eloquent\Traits\Exceptions\NestableMoveException;

/**
 * 含有 id 和 parent_id 结构的表嵌套查询
 *
 */
trait Nestable
{

    /**
     * Boot the Nestable trait for a model.
     *
     * @return void
     */
    public static function bootNestable()
    {
        // 更新设置parent_id时，禁止为自身或者自身的子节点
        static::updating(function ($model) {
            if ($model->parent_id && $model->isDirty('parent_id')) {
                $parent_ids = static::find($model->parent_id)->parent_ids;
                if (in_array($model->id, $parent_ids)) {
                    throw new NestableMoveException('Move forbidden.');
                }
            }
        });

        static::deleting(function ($model) {
            if ($model->children()->count()) {
                throw new NestableDeleteException('Delete forbidden.');
            }
        });
    }

    /**
     * 关联子级别
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * 关联递归子级别
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->child()->with('children');
    }

    /**
     * 关联父级别
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * 关联递归父级别
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parents()
    {
        return $this->parent()->with('parents');
    }

    /**
     * 获取全部父级数组
     * $parents = $model->parents
     *
     * @return array
     */
    public function getParentsAttribute()
    {
        // parents 包括自身
        $parents = [
            $this->id => $this,
        ];

        $parent_id = $this->parent_id;

        // 递归查询父级
        while (true) {
            if ($parent = $this->find($parent_id)) {
                $parents[$parent->id] = $parent;
                if ($parent_id = $parent->parent_id) {
                    continue;
                }
            }
            break;
        }

        return array_reverse($parents, true);
    }

    /**
     * 获取全部父级编号数组
     * $parents = $model->parent_ids
     *
     * @return array
     */
    public function getParentIdsAttribute()
    {
        return array_keys($this->parents);
    }

    /**
     * 获取最顶级的编号
     * $parents = $model->top_id
     *
     * @return integer
     */
    public function getTopIdAttribute()
    {
        return head($this->parent_ids);
    }
}

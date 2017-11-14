<?php
namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

trait Nestable
{
    /**
     * 父列键名
     * @var string
     */
    protected $parentColumn = 'parent_id';

    /**
     * 缓存键名
     *
     * @var string
     */
    protected static $nested_cache_name = 'nested_cache';

    /**
     * 缓存时间
     *
     * @var string
     */
    protected static $nested_cache_time = 365*24*60;

    /**
     * Boot the Nestable trait for a model.
     *
     * @return void
     */
    public static function bootNestable()
    {
        static::$nested_cache_name = static::class.'.'.static::$nested_cache_name;

        static::saved(function() {
            Cache::forget(static::$nested_cache_name);
        });

        static::deleted(function() {
            Cache::forget(static::$nested_cache_name);
        });                
    }    

    /**
     * 获取全部数据父子关系，用于嵌套查询
     * 
     * @return array
     */
    protected function nested($id=null)
    {
        static $nested = [];
        if (empty($nested)) {
            $nested = Cache::remember(static::$nested_cache_name, static::$nested_cache_time, function() {
                return $this->select($this->primaryKey, $this->parentColumn)->get()->pluck($this->parentColumn, $this->primaryKey)->toArray();
            });
        }

        if (func_num_args() == 0) {
            return $nested;
        }

        return isset($nested[$id]) ? $nested[$id] : null;
    }

    /**
     * 获取节点的全部父编号 ancestors
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  array   $parentIds 传递自身
     * @return array
     */
    protected function parentIds($id, $self=false, &$parentIds=[])
    {
        if ($self) {
            $parentIds[] = $id;
        }

        if ($parentId = static::nested($id)) {
            $this->parentIds($parentId, true, $parentIds);
        }

        return array_reverse($parentIds);
    }    

    /**
     * 获取节点的全部子编号 descendants
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  array   $childIds  传递自身
     * @return array
     */
    public function childIds($id, $self=false, &$childIds=[])
    {
        if ($self) {
            $childIds[] = $id;
        }

        foreach (static::nested() as $currentId => $currentParentId) {
            if ($id == $currentParentId) {
                $this->childIds($currentId, true, $childIds);
            }
        }

        return $childIds;        
    }

    /**
     * 获取节点的顶级节点编号
     * 
     * @param  mixed  $id         编号
     * @param  mixed  $rootId     默认顶级编号
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function rootId($id, $rootId=0)
    {
        $parentIds = static::parentIds($id, false);

        if ($parentIds) {
            $rootId = reset($parentIds);
        }

        return $rootId;
    }

    /**
     * 获取顶级节点
     * 
     * @param  mixed  $id 编号
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function root($id)
    {
        $rootId = static::rootId($id);

        return $this->where($this->primaryKey, $rootId)->first();
    }    

    /**
     * 获取全部的父级节点
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function parents($id, $self=false)
    {
        $parentIds = static::parentIds($id, $self);

        return $this->whereIn($this->primaryKey, $parentIds)->get();
    }

    /**
     * 获取全部的子级节点
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function children($id, $self=false)
    {
        $childIds = static::childIds($id, $self);

        return $this->whereIn($this->primaryKey, $childIds)->get();
    }


}

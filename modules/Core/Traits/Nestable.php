<?php
namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

//$data = Region::enabled()->nestArray();
//$data = Region::enabled()->nestJson();
//$data = Region::parentIds($parent_id, true);
//$data = Region::parents($parent_id, true);
//$data = Region::childIds($parent_id);
//$data = Region::top($parent_id);
//$data = Region::enabled()->children($parent_id, true)->toArray();
//$data = Region::enabled()->nestArray(1);
// $data = Region::parent($parent_id);

trait Nestable
{
    /**
     * 根节点的编号，默认为0，可以为字符串
     * @var mixed
     */    
    public $rootId = 0;

    /**
     * 父列键名，数据表中必须包含该项
     * @var string
     */
    public $parentColumn = 'parent_id';

    /**
     * 排序键名，如果为空按照主键排序
     * @var string
     */
    public $orderColumn = 'sort';

    /**
     * 默认排序顺序 asc 或 desc
     * @var string
     */
    public $orderDirection = 'asc';
    
    /**
     * 嵌套数组的子节点键名
     * @var string
     */    
    public $childrenKey = 'children';

    /**
     * 嵌套数组的深度键名
     * @var string
     */     
    public $depthKey = 'depth';

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

        static::addGlobalScope(new NestableScope);            
    }    

    /**
     * 获取全部数据父子关系，用于嵌套查询
     * 
     * @return array
     */
    public function hashTable($id=null)
    {
        static $nested = [];
        if (empty($nested)) {
            $nested = Cache::remember(static::$nested_cache_name, static::$nested_cache_time, function() {
                return $this->select($this->primaryKey, $this->parentColumn)
                ->orderBy($this->orderColumn ?? $this->primaryKey, $this->orderDirection)
                ->get()
                ->pluck($this->parentColumn, $this->primaryKey)
                ->toArray();
            });
        }

        if (func_num_args() == 0) {
            return $nested;
        }

        return isset($nested[$id]) ? $nested[$id] : null;
    }

    /**
     * 获取节点的上级节点编号
     * @param  mixed $id 节点编号
     * @return mixed
     */
    public static function parentId($id)
    {
        return (new static)->hashTable($id);
    }

    /**
     * 实例方法 getParentId
     * 
     * @return mixed
     */
    public function getParentId()
    {
        return $this->{$this->$parentColumn};
    }

    /**
     * 获取父节点
     * @param  mixed $id 节点编号
     * @return mixed
     */
    public static function parent($id)
    {
        $parentId = static::parentId($id);
        $primaryKey = ($instance = new static)->getKeyName();
        return $instance->where($primaryKey, $parentId)->first();      
    }

    /**
     * 实例方法 getParentId
     * 
     * @return mixed
     */
    public function getParent()
    {
        return static::parent($this->getKey());
    }    

    /**
     * 获取节点的全部父编号 ancestors
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  array   $parentIds 传递自身
     * @return array
     */
    public static function parentIds($id, $self=false, &$parentIds=[])
    {
        static $instance = null;

        if (empty($instance)) {
            $instance = new static;
        }

        if ($self) {
            $parentIds[] = $id;
        }

        if ($parentId = $instance->hashTable($id)) {
            $instance->parentIds($parentId, true, $parentIds);
        }

        return array_reverse($parentIds);
    }

    /**
     * 实例方法 getParentId
     * 
     * @param  boolean $self      是否包含自身
     * @return mixed
     */
    public function getParentIds($self=false)
    {
        return static::parentIds($this->getKey(), $self);
    }   

    /**
     * 获取全部的父级节点
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function parents($id, $self=false)
    {
        $parentIds = static::parentIds($id, $self);

        $primaryKey = ($instance = new static)->getKeyName();

        return $instance->whereIn($primaryKey, $parentIds)->get();
    }

    /**
     * 实例方法 getParents
     * 
     * @param  boolean $self      是否包含自身
     * @return mixed
     */
    public function getParents($self=false)
    {
        return static::parents($this->getKey(), $self);
    }

    /**
     * 获取节点的子节点编号
     * 
     * @param  mixed  $id         编号
     * @return array
     */
    public static function childId($id)
    {
        static $instance = null;

        if (empty($instance)) {
            $instance = new static;
        }

        $childId = [];

        foreach ($instance->hashTable() as $currentId => $currentParentId) {
            if ($id == $currentParentId) {
                $childId[] = $currentId;
            }
        }

        return $childId;        
    }

    /**
     * 实例方法 getChildId
     * 
     * @return mixed
     */
    public function getChildId()
    {
        return static::childId($this->getKey());
    }    

    /**
     * 获取节点的全部子编号 descendants
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  array   $childIds  传递自身
     * @return array
     */
    public static function childIds($id, $self=false, &$childIds=[])
    {
        static $instance = null;

        if (empty($instance)) {
            $instance = new static;
        }

        if ($self) {
            $childIds[] = $id;
        }

        foreach ($instance->hashTable() as $currentId => $currentParentId) {
            if ($id == $currentParentId) {
                $instance->childIds($currentId, true, $childIds);
            }
        }

        return $childIds;        
    }

    /**
     * 实例方法 getChildIds
     * 
     * @param  boolean $self 是否包含自身
     * @return mixed
     */
    public function getChildIds($self=false)
    {
        return static::childIds($this->getKey(), $self);
    }        

    /**
     * 获取节点的一级节点编号，如果本身就是一级节点，返回自身
     * 
     * @param  mixed  $id         编号
     * @param  mixed  $rootId     默认顶级编号
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function topId($id)
    {
        $parentIds = static::parentIds($id, true);

        return reset($parentIds);
    }

    /**
     * 实例方法 getTopId
     * 
     * @return mixed
     */
    public function getTopId()
    {
        return static::topId($this->getKey());
    }  

    /**
     * 获取顶级节点
     * 
     * @param  mixed  $id 编号
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function top($id)
    {
        $topId = static::topId($id);

        $primaryKey = ($instance = new static)->getKeyName();

        return $instance->where($primaryKey, $topId)->first();
    }

    /**
     * 实例方法 getTop
     * 
     * @return mixed
     */
    public function getTop()
    {
        return static::top($this->getKey());
    }     
}

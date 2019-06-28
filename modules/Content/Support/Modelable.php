<?php
namespace Modules\Content\Support;

use Illuminate\Support\Facades\Schema;
use Modules\Content\Models\Model;
use Modules\Content\Models\Field;

trait Modelable
{   

    /**
     * 获取扩展模型的编号
     * 
     * @param  string $model_id 模型编号
     * @return model | null
     */        
    public function getExtendModelId($model_id=null)
    {
        return $model_id = $model_id ?? parent::getAttribute('model_id');
    }

    /**
     * 获取扩展模型的类名
     * 
     * @param  string $model_id 模型编号
     * @return model | null
     */    
    public function getExtendModelClass($model_id=null)
    {
        $model_id = $this->getExtendModelId($model_id);

        $class = 'Modules\\Content\\Extend\\'.studly_case($model_id).'Model';

        if (class_exists($class)) {
            return $class;
        }

        return null;
    }

    /**
     * 获取扩展模型的实例
     * 
     * @param  string $model_id 模型编号
     * @return model | null
     */
    public function getExtendModel($model_id=null)
    {
        static $extends = [];

        $model_id = $this->getExtendModelId($model_id);

        if (! array_key_exists($model_id, $extends)) {

            if ($class = $this->getExtendModelClass($model_id)) {
                $model = $class::findOrNew($this->getKey());
            }  else {
                $model = null;
            }

            $extends[$model_id] = $model;           
        }
        
        return $extends[$model_id];
    }

    /**
     * 获取扩展模型的关联函数名称
     * 
     * @param  string $model_id 模型编号
     * @return string
     */
    public function getExtendRelation($model_id=null)
    {
        $model_id = $this->getExtendModelId($model_id);

        $relation = studly_case($model_id).'Relation';

        if ($model_id && method_exists($this, $relation)) {
            return $relation;
        }

        return null;
    }

    /**
     * 模型筛选
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModel($query, $model_id=null, $relationship=true)
    {
        if($model_id) {
            $models =  str_array($model_id, ',');

            if ($relationship) {
                foreach ($models as $model) {
                    if ($relation = $this->getExtendRelation($model)) {
                        $query->with($relation);
                    }
                }
            }

            if (count($models) == 1) {
                return $query->where('model_id', reset($models));
            }
            
            return $query->whereIn('model_id', $models);         
        }

        return $query;
    } 
    

    /**
     * 扩展填充，允许填充扩展模型字段
     * 
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @return $this
     */
    public function fill(array $attributes)
    {
        // 去除空值，保存为null
        // $attributes = array_filter($attributes, function($item) {
        //     if (is_string($item) && trim($item) === '') {
        //         return false;
        //     }
        //     return true;
        // });

        parent::fill($attributes);

        if ($extendModel = $this->getExtendModel()) {
            $extendModel->fill($attributes);
        }

        return $this;
    }

    /**
     * 扩展保存，自动保存扩展类数据
     * 
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if (parent::save($options) && $extendModel = $this->getExtendModel()) {
            $extendModel->save();
            return true;
        }

        return false;
    }

    /**
     * 当前模型是否含有属性
     * @param  string  $key
     * @return boolean
     */
    public function hasAttribute($key)
    {
        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key) ||
            method_exists(self::class, $key) ||
            method_exists($this, $key) ||
            $this->relationLoaded($key)) {
            return true;
        }

        return false;
    }

    /**
     * 获取属性，自动获取扩展模型属性
     * 
     * @param  string $key 属性名称
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            return parent::getAttribute($key);
        }

        if (parent::getAttribute('id') && $extendRelation = $this->getExtendRelation()) {
            return $this->$extendRelation->getAttribute($key);
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        if ($extend = $this->getExtendModel()) {
            if ($extend->isFillable($key)) {
                $extend->setAttribute($key, $value);
            }
        }

        return $this;
    }
}

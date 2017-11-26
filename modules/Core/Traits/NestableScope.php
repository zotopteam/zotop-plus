<?php
namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NestableScope implements Scope
{

    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['Child','Children','NestArray','NestJson'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addChild(Builder $builder)
    {
        $builder->macro('child', function (Builder $builder, $id) {
            $model = $builder->getModel();
            return $builder->where($model->parentColumn, $id)->get();
        });
    }
    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addChildren(Builder $builder)
    {
        $builder->macro('children', function (Builder $builder, $id, $self = false) {
            $model     = $builder->getModel();
            
            // 如果编号不为空并且等于根编号，返回子节点，否则返回全部节点
            if ($id && $id != $model->rootId) {

                $childIds = $model::childIds($id, $self);

                return $builder->whereIn($model->getKeyName(), $childIds)->get();
            }

            return $builder->get();
        });
    }

    /**
     * Add the nestArray extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addNestArray(Builder $builder)
    {
        /**
         * 获取嵌套数据
         * 
         * @param  mixed $id 开始节点，默认获取全部
         * @return array
         */        
        $builder->macro('nestArray', function (Builder $builder, $id=null) {
            
            $model = $builder->getModel();

            // 如果编号不为空并且等于根编号，返回子节点，否则返回全部节点
            if ($id && $id != $model->rootId) {
                $datalist = $builder->children($id, false);
            } else {
                $datalist = $builder->orderBy($model->orderColumn ?? $model->primaryKey, $model->orderDirection)->get();
            }

            $nestArray = array_nest(
                $datalist->toArray(),
                $id ?? $model->rootId,
                0,
                $model->getKeyName(),
                $model->parentColumn,
                $model->childrenKey,
                $model->depthKey
            );

            return $nestArray;   
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addNestJson(Builder $builder)
    {
        /**
         * 获取嵌套数据
         * 
         * @param  mixed $startId 开始节点，默认获取全部
         * @return json
         */        
        $builder->macro('nestJson', function (Builder $builder, $startId=null) {
            return json_encode($builder->nestArray($startId));  
        });
    }               
}

namespace Modules\Content\Extend;

trait Extendable
{
    @foreach ($models as $model)

    /**
     * 获取内容表和模型[{{$model->name}}]的一对一关联
     * The relation of the model: {{$model->id}}
     */
    public function {{studly_case($model->id)}}Relation()
    {
        return $this->hasOne('Modules\Content\Extend\{{studly_case($model->id)}}Model', 'id', 'id');
    }
    
    @endforeach

}

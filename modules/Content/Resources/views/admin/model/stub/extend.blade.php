namespace Modules\Content\Extend;

use {{$model->model}} as ContentModel;

/**
 * 内容扩展模型：{{$model->name}} {{$model->id}}
 * 自动创建时间：{{now()}}
 */
class {{studly_case($model->id)}}Model extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = '{{$model->table}}';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = {!! $model->fillable !!};

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = {!! $model->casts !!};
}

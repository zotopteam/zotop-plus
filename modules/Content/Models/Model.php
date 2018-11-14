<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Modules\Core\Traits\UserRelation;
use Modules\Content\Models\Field;
use Modules\Content\Models\Content;


class Model extends BaseModel
{
    use UserRelation;
	
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'content_model';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['id','icon','name','description','module','model','template','posts','sort','disabled','user_id'];
	
	
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
    //protected $casts = [];
	
	
    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    //public $timestamps = false;
    
    /**
     * id 字符串格式，关闭自动增长
     *
     * @var bool
     */    
    public $incrementing = false;

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // 为安全考虑，禁止删除非空的模型
        static::deleting(function($model) {

            // 如果有自定义字段，不能删除
            if (Field::where('model_id', $model->id)->where('system', 0)->count()) {
                abort(403, trans('content::model.delete.notempty'));
            }

            // 如果已经有数据，不能删除
            if (Content::where('model_id', $model->id)->count()) {
                abort(403, trans('content::model.delete.notempty'));
            }

            Field::where('model_id', $model->id)->delete();          
        });

        // 模型有自定义字段和数据后，禁止修改模型标识 id
        static::updating(function($model) {
            // 如果id改变
            if ($model->isDirty('id')) {

                // 有自定义字段时（已经生成自定义字段的附属表），禁止修改
                if (Field::where('model_id', $model->getOriginal('id'))->where('system', 0)->count()) {
                    abort(403, trans('content::model.dirtyid.forbidden'));
                }

                // 有数据的时候禁止修改
                if (Content::where('model_id', $model->getOriginal('id'))->count()) {
                    abort(403, trans('content::model.dirtyid.forbidden'));
                }

                Field::where('model_id', $model->getOriginal('id'))->update([
                    'model_id' => $model->id
                ]);
            }
        });      
    }

    /**
     * 关联字段
     */
    public function field()
    {
        return $this->hasMany('Modules\Content\Models\Field', 'model_id', 'id');
    }  

    /**
     * 关联的数据
     */
    public function content()
    {
        return $this->hasMany('Modules\Content\Models\Content', 'model_id', 'id');
    }

}

<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Modules\Core\Traits\UserRelation;
use Modules\Content\Models\Field;
use Modules\Content\Models\Content;
use Modules\Content\Support\ModelTable;
use Modules\Content\Support\ModelHelper;

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

            // 如果已经有数据，不能删除
            if (Content::where('model_id', $model->id)->count()) {
                abort(403, trans('content::model.delete.notempty'));
            }

            // 删除字段
            Field::where('model_id', $model->id)->delete();

            // 删除关联表
            $table = ModelTable::find($model->id);
            $table->drop();                    
        });

        // 修改模型标识 id 时对应的关联数据
        static::updating(function($model) {
            // 如果id改变
            if ($model->isDirty('id')) {

                // 修改字段关联
                Field::where('model_id', $model->getOriginal('id'))->update([
                    'model_id' => $model->id
                ]);

                // 修改数据关联
                Content::where('model_id', $model->getOriginal('id'))->update([
                    'model_id' => $model->id
                ]);

                // 修改表名称
                $table = ModelTable::find($model->getOriginal('id'));
                $table->rename($model->id);   
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

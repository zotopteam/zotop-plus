<?php

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use App\Traits\UserRelation;
use Modules\Content\Models\Field;
use Modules\Content\Models\Content;
use Modules\Content\Support\ModelTable;
use Modules\Content\Support\ModelHelper;

class Model extends LaravelModel
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
    protected $fillable = ['id', 'icon', 'name', 'description', 'module', 'model', 'table', 'fillable', 'casts', 'view', 'nestable', 'posts', 'sort', 'disabled', 'user_id'];

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
    protected $casts = [
        'fillable' => 'array',
        'casts'    => 'array',
    ];

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
     * booted
     * 
     * @return void
     */
    protected static function booted()
    {
        // 创建模型
        static::creating(function ($model) {
            $model->module = $model->module ?: 'content';
        });

        // 修改模型标识 id 时对应的关联数据
        static::updating(function ($model) {
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
                if ($model->table) {
                    $table = ModelTable::find($model->getOriginal('id'));
                    $table->rename($model->id);
                    $model->setAttribute('table', $table->name());
                }
            }
        });

        // 为安全考虑，禁止删除非空的模型
        static::deleting(function ($model) {

            // 如果已经有数据，不能删除
            if (Content::where('model_id', $model->id)->count()) {
                abort(403, trans('content::model.delete.notempty'));
            }

            // 删除字段
            Field::where('model_id', $model->id)->delete();

            // 删除关联表
            if ($model->table) {
                $table = ModelTable::find($model->id);
                $table->drop();
                $model->setAttribute('table', null);
            }
        });

        // 保存模型时
        static::saved(function ($model) {
            ModelHelper::refreshExtend($model);
        });

        // 删除模型时
        static::deleted(function ($model) {
            ModelHelper::refreshExtend($model);
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

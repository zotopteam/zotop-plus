<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Content\Support\ModelTable;
use Module;

class Field extends Model
{
	
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'content_field';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['model_id','label','type','name','default','settings','help','post','search','system','col','row','sort','disabled'];
	
	
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];
	
	
    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'json'
    ];
	
	
    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    //public $timestamps = false;

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // 新增设置
        static::creating(function($field) {

            // 预处理数据
            $field = static::preproccess($field);
            $field->sort = static::where('model_id', $field->model_id)->max('sort') + 1;  

            // 创建自定义字段
            if (! $field->system) {
                $table = ModelTable::find($field->model_id);
                $table->addColumn($field);
            }

            unset($field->method);
        });

        // 更新设置
        static::updating(function($field) {
            
            $field = static::preproccess($field);

            // 必填字段不允许禁用
            if (array_get($field->settings, 'required') && $field->disabled) {
                abort(403, trans('content::field.disable.required'));
            }

            // 更新自定义字段
            if (! $field->system) {
                
                $table = ModelTable::find($field->model_id);
                
                if ($field->isDirty('name')) {
                    $table->renameColumn($field->getOriginal('name'), $field->name);
                }

                $table->changeColumn($field);
            }
            
            unset($field->method);
        });

        static::deleting(function($field) {
            // 不允许删除系统字段
            if ($field->system) {
                abort(403,'Can not delete system field!');
            }

            // 删除字段
            $table = ModelTable::find($field->model_id);
            $table->dropColumn($field->name);
        });    
    }

    /**
     * 预处理字段，完善字段数据
     * @param  object $field 字段对象
     * @return object
     */
    public static function preproccess($field)
    {
        $types = static::types($field->model_id);

        // 合并默认设置
        if ($settings = array_get($types, $field->type.'.settings')) {
            $field->settings = array_merge($settings, $field->settings);
        }

        // 补充字段创建方法名称 (对应laravel的数据迁移方法名称)
        $field->method = array_get($types, $field->type.'.method');

        return $field;
    }

    /**
     * 获取模型支持的字段类型
     * 
     * @param  string $model_id 模型编号
     * @return array
     */
    public static function types($model_id)
    {
        static $types = [];

        if (empty($types)) {
            $types = Module::data('content::field.types', ['model_id'=>$model_id]);
        }

        return $types;
    }   

    /**
     * 查询系统或者自定义字段
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $system true=系统字段 false=自定义字段
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSystem($query, $system=true)
    {
        return $query->where('system', ($system ? 1 : 0));
    }

    /**
     * 获取字段类型名称
     * @return string
     */
    public function getTypeNameAttribute($value)
    {
        $types = static::types($this->model_id);

        return array_get($types, $this->type.'.name');
    }
}

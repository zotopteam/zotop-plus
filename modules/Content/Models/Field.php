<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
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

        // 更新设置
        static::updating(function($field) {
            
            $types = Module::data('content::field.types');

            // 合并默认设置
            if ($settings = array_get($types, $field->type.'.settings')) {
                $field->settings = array_merge($settings, $field->settings);
            }

            // 更新自定义字段
            // ……
            
        });      
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
}

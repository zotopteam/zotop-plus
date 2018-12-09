<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Traits\UserRelation;
use Module;
use Action;

class Block extends Model
{
    use UserRelation;
    
    protected $table = 'block';

    protected $fillable = ['category_id','type','slug','name','description','rows','data','template','interval','fields','commend','sort','user_id','disabled'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'data'   => 'json',
        'fields' => 'json',
    ];

    /**
     * 全局作用域
     * 
     * @return null
     */
    protected static function boot()
    {
        parent::boot();

        // 保存后
        static::saved(function ($model) {
            Action::fire('block.saved', $model);
        });

        // 保存后
        static::deleted(function ($model) {
            Action::fire('block.deleted', $model);
        });            
    }

    /**
     * 区块类型
     * 
     * @param  string $type    类型
     * @param  string $field   字段键名
     * @param  mixed $default 默认值
     * @return mixed
     */
    public static function type($type=null, $field=null, $default=null)
    {
        $types = Module::data('block::types');

        if (empty($type)) {
            return $types;
        }

        if (empty($field)) {
            return $types[$type] ?? [];
        }

        return $types[$type][$field] ?? $default;
    }

    /**
     * 排序
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query)
    {
        return $query->orderby('sort', 'asc')->orderby('id', 'asc');
    }    

    /**
     * 区块类型名称
     *
     * @param  string  $value
     * @return string
     */
    public function getTypeNameAttribute($value)
    {        
        return static::type($this->type, 'name');
    }

    /**
     * 模板代码
     *
     * @param  string  $value
     * @return string
     */
    public function getSlugIncludeAttribute($value)
    {        
        return '{block slug="'.$this->slug.'"}';
    }

    /**
     * 数据编号
     *
     * @param  string  $value
     * @return string
     */
    public function getDataIdAttribute($value)
    {        
        return 'block-'.$this->id;
    }    

    /**
     * 获取字段，为了排序需要去掉key名
     *
     * @param  string  $value
     * @return void
     */
    public function getFieldsAttribute($value)
    {
        return $value ? array_values(json_decode($value,true)) : $value;
    }

}

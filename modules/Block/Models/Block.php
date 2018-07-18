<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Module;

class Block extends Model
{
    protected $table = 'block';
    protected $fillable = ['category_id','type','code','name','description','rows','data','template','interval','fields','commend','sort','user_id','disabled'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];

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
     * 查看率
     *
     * @param  string  $value
     * @return string
     */
    public function getTypeNameAttribute($value)
    {        
        return static::type($this->type, 'name');
    }

    /**
     * 查看率
     *
     * @param  string  $value
     * @return string
     */
    public function getCodeIncludeAttribute($value)
    {        
        return '{block code="'.$this->code.'"}';
    }

    /**
     * 查询排序
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSorted($query)
    {
        return $query->orderby('sort', 'asc')->orderby('id', 'asc');
    }       
}

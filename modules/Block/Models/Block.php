<?php

namespace Modules\Block\Models;

use Action;
use App\Support\Eloquent\Model;
use App\Traits\UserRelation;
use Module;

class Block extends Model
{
    use UserRelation;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'block';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['category_id', 'type', 'slug', 'name', 'description', 'rows', 'data', 'view', 'interval', 'fields', 'commend', 'sort', 'user_id', 'disabled'];

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
     * booted
     *
     * @return void
     */
    protected static function booted()
    {
        // 保存后
        static::saved(function ($model) {
            Action::fire('block.saved', $model);
        });

        // 删除后
        static::deleted(function ($model) {
            Action::fire('block.deleted', $model);
        });
    }

    /**
     * 区块类型
     *
     * @param string $type 类型
     * @param string $field 字段键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function type($type = null, $field = null, $default = null)
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
     * @param string $value
     * @return string
     */
    public function getTypeNameAttribute($value)
    {
        return static::type($this->type, 'name');
    }

    /**
     * 模板代码
     *
     * @param string $value
     * @return string
     */
    public function getSlugIncludeAttribute($value)
    {
        return '<x-block slug="' . $this->slug . '" />';
    }

    /**
     * 数据编号
     *
     * @param string $value
     * @return string
     */
    public function getSourceIdAttribute($value)
    {
        return "block-{$this->id}";
    }

    /**
     * 获取字段，为了排序需要去掉key名
     *
     * @param string $value
     * @return void
     */
    public function getFieldsAttribute($value)
    {
        return $value ? array_values(json_decode($value, true)) : $value;
    }
}

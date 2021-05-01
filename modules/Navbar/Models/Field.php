<?php

namespace Modules\Navbar\Models;

use Zotop\Modules\Facades\Module;
use Zotop\Support\Eloquent\Model;
use Zotop\Support\Enums\BoolEnum;
use Zotop\Support\Enums\SortEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Field extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'navbar_field';

    /**
     * 可以被批量赋值的属性
     *
     * @var array
     */
    protected $fillable = ['id', 'navbar_id', 'parent_id', 'label', 'type', 'name', 'default', 'settings', 'help', 'sort', 'disabled', 'created_at', 'updated_at'];

    /**
     * 不可被批量赋值的属性
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
        'settings' => 'json',
    ];

    /**
     * 执行模型是否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 启动
     *
     * @author Chen Lei
     * @date 2021-01-31
     */
    public static function booted()
    {
        // 默认排序
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('sort', SortEnum::ASC)->orderBy('id', SortEnum::ASC);
        });
    }

    /**
     * 只查询可用数据作用域
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('disabled', BoolEnum::NO);
    }

    /**
     * 获取模型支持的字段类型
     *
     * @param string|null $type
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     * @throws \Zotop\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function types($type = null, $key = null, $default = null)
    {
        static $types = [];

        if (empty($types)) {
            $types = Module::data('navbar::field.types');
        }

        if (isset($type) && isset($key)) {
            return Arr::get($types, $type . '.' . $key, $default);
        }

        if (isset($type)) {
            return Arr::get($types, $type);
        }

        return collect($types);
    }

}

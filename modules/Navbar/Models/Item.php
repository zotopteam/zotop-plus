<?php

namespace Modules\Navbar\Models;

use Zotop\Database\Eloquent\Model;
use Zotop\Database\Eloquent\Traits\Nestable;
use Zotop\Enums\BoolEnum;
use Zotop\Enums\SortEnum;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use Nestable;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'navbar_item';

    /**
     * 可以被批量赋值的属性
     *
     * @var array
     */
    protected $fillable = ['id', 'navbar_id', 'parent_id', 'title', 'link', 'custom', 'sort', 'disabled', 'created_at', 'updated_at'];

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
        'custom' => 'json',
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
}

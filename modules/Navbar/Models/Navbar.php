<?php

namespace Modules\Navbar\Models;

use App\Support\Eloquent\Model;
use App\Support\Enums\BoolEnum;
use App\Support\Enums\SortEnum;
use Illuminate\Database\Eloquent\Builder;

class Navbar extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'navbar';

    /**
     * 可以被批量赋值的属性
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'slug', 'fields', 'sort', 'disabled', 'created_at', 'updated_at'];

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
        'fields' => 'json',
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
     * 关联导航项
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Chen Lei
     * @date 2021-02-01
     */
    public function item()
    {
        return $this->hasMany(Item::class, 'navbar_id', 'id');
    }
}

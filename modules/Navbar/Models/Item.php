<?php

namespace Modules\Navbar\Models;

use App\Support\Eloquent\Model;
use App\Support\Eloquent\Traits\Nestable;
use App\Support\Enums\BoolEnum;

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

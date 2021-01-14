<?php

namespace Modules\Navbar\Models;

use App\Support\Eloquent\Model;

class Item extends Model
{
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
    protected $fillable = ['id', 'parent_id', 'title', 'link', 'custom', 'sort', 'status', 'created_at', 'updated_at'];

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

}

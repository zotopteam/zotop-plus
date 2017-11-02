<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * 定义填充字段
     * 
     * @var array
     */
    protected $fillable   = ['key','value','module'];
    
    /**
     * 禁止写入的字段
     *
     * @var array
     */
    protected $guarded    = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}

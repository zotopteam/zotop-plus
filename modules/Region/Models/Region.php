<?php

namespace Modules\Region\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Nestable;

class Region extends Model
{
    use Nestable;

    /**
     * 关闭时间戳
     * @var boolean
     */
    public $timestamps = FALSE;
    
    /**
     * 可填充项
     * @var array
     */
    protected $fillable = ['parent_id', 'title', 'sort'];

    /**
     * 限制查询只包括启用的数据。
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('disabled', 0);
    }
   
}

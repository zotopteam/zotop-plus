<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Module;

class Role extends Model
{
    protected $fillable = ['name','description','permissions'];

    /**
     * 获取全部模块的权限
     * @return [type] [description]
     */
    public static function permissions()
    {
        
    }    
}

<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Module;

class Role extends Model
{
    protected $fillable = ['name','description','permissions'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];
}

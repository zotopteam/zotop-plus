<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;

class Role extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'permissions'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * 拥有此角色的用户
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
}

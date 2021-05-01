<?php

namespace Modules\Core\Models;

use Zotop\Support\Eloquent\Model;

class Role extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['type', 'name', 'description', 'permissions'];

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
}

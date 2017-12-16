<?php

namespace Modules\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Core\Models\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * 定义填充字段
     * 
     * @var array
     */
    protected $fillable   = ['username','password','email','mobile','modelid','nickname','gender','avatar','sign','login_times','login_at','login_ip','disabled','token','remember_token','created_at','updated_at'];
    
    /**
     * 禁止写入的字段
     *
     * @var array
     */
    protected $guarded    = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'password', 'token', 'remember_token',
    ];

    /**
     * 角色关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }   

    /**
     * 判定当前用户的model
     * @param  mixed $modelid 模型编号
     * @return boolean
     */
    public function isModel($modelid)
    {
        if (is_array($modelid)) {
            return in_array($this->modelid, $modelid) ? true : false;
        }

        return $this->modelid == $modelid ? true : false;
    }

    /**
     * 获取当然用户全部的权限
     * @return array
     */
    public function getPermissions()
    {
        static $permissions = [];

        if (empty($permissions)) {
            // 合并权限
            $this->roles()->get()->each(function($item, $key) use(&$permissions) {
                $permissions = array_merge($permissions, $item->permissions);
            });
        }

        return $permissions;
    }

    /**
     * 是否拥有权限，如果为多个权限值验证，必须同时拥有多个权限才能通过验证
     * 
     * @param  mixed $permissions 权限
     * @return bool
     */
    public function allow($permissions)
    {
        // 创始用户拥有全部权限
        if ($this->isSuper()) {
           return true;
        }
        
        if (is_string($permissions)) {
            $permissions = func_get_args();
        }

        //角色拥有的权限
        $rolePermissions = $this->getPermissions();
        
        // 多个权限限制必须全部为真才通过权限验证
        foreach ($permissions as $permission) {
            if (! $this->checkPermission($rolePermissions, $permission)) {
                return false;
            }
        }

        return true;        
    }

    /**
     * 检查权限是否为真
     * 
     * @param  array $permissions 权限
     * @param  string $permission
     * @return bool
     */
    public function checkPermission(array $permissions, $permission)
    {
        if (array_key_exists($permission, $permissions)) {
            return true;
        }

        // 允许通配符 user.can.* 的验证
        foreach ($permissions as $value) {
            if ((str_is($permission, $value) || str_is($value, $permission))) {
                return true;
            }
        }

        return false;        
    }

    /**
     * Dynamically pass missing methods to the user.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // isAdmin,isSuper,isMember
        if (starts_with($method, 'is')) {
            return $this->isModel(strtolower(substr($method, 2)));
        }

        return parent::__call($method, $parameters);
    }       
}

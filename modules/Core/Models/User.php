<?php

namespace Modules\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'token', 'password', 'remember_token',
    ];

    /**
     * 当前登陆的用户是否为管理员 TODO：暂时写在此处，使用Repositories更佳
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->modelid == 'admin' ? true : false;
    }
}

<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Traits\UserIp;
use App\Traits\UserRelation;

class Log extends Model
{
    use UserRelation, UserIp;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'logs';


    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['type', 'user_id', 'user_ip', 'url', 'module', 'controller', 'action', 'content', 'request'];


    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'request'  => 'json',
    ];


    /**
     * booted
     * 
     * @return void
     */
    protected static function booted()
    {
        // 删除超出有效期的日志
        static::saved(function ($log) {
            static::where('created_at', '<', now()->modify('-' . config('core.log.expire', 30) . ' days'))->delete();
        });
    }
}

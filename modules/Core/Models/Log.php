<?php
namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\UserRelation;
use Modules\Core\Traits\UserIp;

class Log extends Model
{
	use UserRelation,UserIp;

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
    protected $fillable = ['type','user_id','user_ip','url','module','controller','action','content','request','response'];
	
	
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
        'response' => 'json',
    ];
	
    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function($log) {
            
            // 删除超出有效期的日志
            static::where('created_at', '<', now()->modify('-'.config('core.log.expire', 30).' days'))->delete();

            $log->type       = $log->type ?? 'unknown';
            $log->module     = app('current.module');
            $log->controller = app('current.controller');
            $log->action     = app('current.action');
            $log->url        = \Request::fullUrl();
            $log->request    = \Request::except(['_token']);
            $log->response   = $log->response ?? [];
        });
    }
}

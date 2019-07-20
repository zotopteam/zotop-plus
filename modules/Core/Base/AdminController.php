<?php
namespace Modules\Core\Base;

use Modules\Core\Models\Log;

class AdminController extends BaseController
{
    /**
     * 消息提示
     * 
     * @param  array  $msg 消息内容
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function message(array $msg)
    {
        // 记录系统操作日志
        if (config('core.log.enabled')) {
            Log::create([
                'type'    => array_get($msg, 'type'),
                'content' => array_get($msg, 'content'),
            ]);
        }

        return parent::message($msg);
    }
}

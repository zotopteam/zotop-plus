<?php

namespace App\Modules\Routing;

use App\Modules\Routing\JsonMessageResponse;

class ApiController extends Controller
{
    /**
     * 消息提示
     * 
     * @param  array  $msg 消息内容
     * @return JsonMessageResponse
     */
    public function message(array $msg)
    {
        // 将赋值数据填入消息中
        $msg['data'] = $this->data;

        return new JsonMessageResponse($msg);
    }

    /**
     * 消息提示：success
     *
     * @param string $message 消息内容
     * @param integer $code 消息代码 0-1000 为success
     * @param integer $time 跳转或者消息提示时间
     * @return JsonMessageResponse
     */
    public function success(string $message, $code = 0, $time = 1)
    {
        return $this->message([
            'code'    => $code,
            'message' => $message,
            'time'    => $time
        ]);
    }


    /**
     * 消息提示：error
     * 
     * @param  string  $message  消息内容
     * @param  integer $code 错误代码
     * @param  integer $time 跳转或者消息提示时间
     * @return JsonMessageResponse
     */
    public function error($message, $code = 1001, $time = 5)
    {
        return $this->message([
            'code'    => $code,
            'message' => $message,
            'time'    => $time
        ]);
    }
}

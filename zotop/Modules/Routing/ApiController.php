<?php

namespace Zotop\Modules\Routing;

class ApiController extends Controller
{

    /**
     * 消息提示
     *
     * @param array $msg 消息内容
     * @return \Zotop\Modules\Routing\JsonMessageResponse
     * @author Chen Lei
     * @date 2020-11-15
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
     * @param string|null $message 消息内容
     * @param integer $code 消息代码 0-1000 为success
     * @param integer $time 跳转或者消息提示时间
     * @return \Zotop\Modules\Routing\JsonMessageResponse
     */
    public function success($message = null, $code = 0, $time = 1)
    {
        return $this->message([
            'code'    => $code,
            'message' => $message,
            'time'    => $time,
        ]);
    }


    /**
     * 消息提示：error
     *
     * @param string $message 消息内容
     * @param integer $code 错误代码
     * @param integer $time 跳转或者消息提示时间
     * @return \Zotop\Modules\Routing\JsonMessageResponse
     */
    public function error($message, $code = 1001, $time = 5)
    {
        return $this->message([
            'code'    => $code,
            'message' => $message,
            'time'    => $time,
        ]);
    }
}

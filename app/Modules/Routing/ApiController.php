<?php

namespace App\Modules\Routing;

class ApiController extends BaseController
{
    /**
     * 消息提示
     * 
     * @param  array  $msg 消息内容
     * @return json
     */
    public function message(array $msg)
    {
        // 将赋值数据填入消息中
        $msg['data'] = $this->data;

        return response()->json($msg);
    }

    /**
     * 消息提示：success
     * 
     * @param  mixed  $content  消息内容 字符串或者数组
     * @param  string  $url  跳转路径
     * @param  integer $time 跳转或者消息提示时间
     * @return json
     */
    public function success($content, $url='', $time=1)
    {
        return $this->message([
            'type'    => 'success',
            'content' => $content,
            'url'     => $url,
            'time'    => $time
        ]);
    }


    /**
     * 消息提示：error
     * 
     * @param  mixed  $content  消息内容 字符串或者数组
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function error($content, $code=1, $time=5)
    {
        return $this->message([
            'type'    => 'error',
            'content' => $content,
            'code'    => $code,
            'time'    => $time
        ]);
    }    
}

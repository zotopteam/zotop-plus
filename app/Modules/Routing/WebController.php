<?php

namespace App\Modules\Routing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Request;

class WebController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * view
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * WebController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->view = $this->app['view'];
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-11-15
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        // 默认view为: module::controller/action
        if (empty($view)) {
            $view = $this->app['current.module'] . '::' . $this->app['current.controller'] . '.' . $this->app['current.action'];
        }

        // 转换模板数据
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        // 合并模板数据
        $data = array_merge($this->data, $data);

        // 生成 view
        return $this->view->make($view, $data, $mergeData);
    }

    /**
     * 消息提示
     *
     * @param array $msg
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-11-15
     */
    public function message(array $msg)
    {
        // 将赋值数据填入消息中
        $msg['data'] = $this->data;

        // 如果请求为ajax，则输出json数据
        if (Request::expectsJson()) {
            return new JsonMessageResponse($msg);
        }

        // 返回view
        return $this->view->make("message.{$msg['type']}", $msg);
    }

    /**
     * 消息提示：success
     *
     * @param mixed $content 消息内容 字符串或者数组
     * @param string|null $url 跳转路径
     * @param integer $time 跳转或者消息提示时间
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function success($content, $url = null, $time = 1)
    {
        return $this->message([
            'type'    => 'success',
            'content' => $content,
            'url'     => $url,
            'time'    => $time,
        ]);
    }


    /**
     * 消息提示：error
     *
     * @param mixed $content 消息内容 字符串或者数组
     * @param integer $time 跳转或者消息提示时间
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function error($content, $time = 5)
    {
        return $this->message([
            'type'    => 'error',
            'content' => $content,
            'time'    => $time,
        ]);
    }
}

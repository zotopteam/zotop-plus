<?php

namespace Modules\Core\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Module;
use Theme;
use Filter;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */    
    protected $app;


    /**
     * view 实例
     * 
     * @var object
     */
    protected $view;

    /**
     * view 数据
     * 
     * @var array
     */
    protected $data = [];


    /**
     * 基础控制器，所有的控制器都要继承自此控制器
     */
    public function __construct()
    {
        // app实例
        $this->app = app();

        if ($this->app->runningInConsole() === true) {
            return;
        }

        // view实例
        $this->view   = $this->app['view'];

        static::boot();    
    }

    /**
     * Boot all of the bootable traits on the controller
     */
    public static function boot()
    {
        $class = static::class;
        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot'.class_basename($trait);
            if (method_exists($class, $method)) {
                forward_static_call([$class, $method]);
            }            
        }
    }


    /**
     * 传入参数, 支持链式
     * 
     * @param  string|array $key 参数名
     * @param  mixed $value 参数值
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $key 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     * 
     * @access protected
     * @param string $key 模板显示变量
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * 检测模板变量是否被设定
     * 
     * @access protected
     * @param string $key 模板显示变量
     * @return bool
     */
    public function __isset($key) {
        return isset($this->data[$key]);
    }

    /**
     * 删除模板变量
     * 
     * @access protected
     * @param string $key 模板显示变量
     * @return bool
     */
    public function __unset($key) {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }    

    /**
     * 显示View
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        // 默认view为: controller/action
        if (empty($view)) {
            $view = $this->app['current.controller'].'.'.$this->app['current.action'];
        }

        // 转换模板数据
        $data = ($data instanceof Arrayable) ? $data->toArray() : $data;

        // 合并模板数据
        $data = array_merge($this->data, $data);

        // 生成 view
        return $this->view->make($view, $data, $mergeData);
    }


    /**
     * 消息提示
     * 
     * @param  array  $msg 消息内容
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function message(array $msg)
    {
        // 将赋值数据填入消息中备用
        $msg['data'] = $this->data;

        //如果请求为ajax，则输出json数据
        if (\Request::expectsJson()) {
            return response()->json($msg);
        }

        // 返回view
        return $this->with('msg', $msg)->view('core::msg');
    }

    /**
     * 消息提示：success
     * 
     * @param  mixed  $content  消息内容 字符串或者数组
     * @param  string  $url  跳转路径
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function success($content, $url='', $time=1)
    {
        return $this->message([
            'state'   => true,
            'type'    => 'success',
            'icon'    => 'fa fa-check-circle',
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
    public function error($content, $time=5)
    {
        return $this->message([
            'state'   => false,
            'type'    => 'error',
            'icon'    => 'fa fa-times-circle',
            'content' => $content,
            'time'    => $time
        ]);
    }
}

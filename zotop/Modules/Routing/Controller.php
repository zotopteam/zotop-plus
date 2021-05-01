<?php

namespace Zotop\Modules\Routing;

use Illuminate\Container\Container;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use DispatchesJobs;

    /**
     * app实例
     *
     * @var mixed|\Illuminate\Foundation\Application
     */
    protected $app;

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
        $this->app = Container::getInstance();

        if ($this->app->runningInConsole() === true) {
            return;
        }

        static::boot();
    }

    /**
     * Boot all of the bootable traits on the controller
     */
    public static function boot()
    {
        $class = static::class;
        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot' . class_basename($trait);
            if (method_exists($class, $method)) {
                forward_static_call([$class, $method]);
            }
        }
    }


    /**
     * 传入参数, 支持链式
     *
     * @param string|array $key 参数名
     * @param mixed $value 参数值
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
    public function __get(string $key)
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
    public function __isset(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * 删除模板变量
     *
     * @param $key
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function __unset($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

}

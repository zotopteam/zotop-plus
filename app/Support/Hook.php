<?php

namespace App\Support;

use Exception;
use Illuminate\Support\Traits\Macroable;

abstract class Hook
{
    use Macroable;

    /**
     * 存储hook
     *
     * @var \Illuminate\Support\Collection
     */
    protected $listeners;

    /**
     * 初始化 listeners
     */
    public function __construct()
    {
        $this->listeners = collect([]);
    }

    /**
     * 监听Hook
     *
     * @param string $hook 钩子名称
     * @param mixed $callback 回调
     * @param integer $priority 执行优先级，默认为50，越小越靠前执行
     * @return mixed
     */
    public function listen(string $hook, $callback, $priority = 50)
    {
        $this->listeners->push([
            'hook'     => $hook,
            'callback' => $callback,
            'priority' => $priority,
        ]);

        return $this;
    }

    /**
     * 删除hook
     *
     * @param string $hook 钩子名称
     * @param null $callback
     * @return mixed
     */
    public function forget(string $hook, $callback = null)
    {
        if ($callback) {
            $this->listeners->where('hook', $hook)->where('callback', $callback)->each(function ($listener, $key) {
                $this->listeners->forget($key);
            });
        } else {
            $this->listeners->where('hook', $hook)->each(function ($listener, $key) {
                $this->listeners->forget($key);
            });
        }

        return $this;
    }

    /**
     * 获取排序过的监听器
     *
     * @param string $hook
     * @return \Illuminate\Support\Collection
     */
    public function listeners(string $hook)
    {
        return $this->listeners->where('hook', $hook)->sortBy('priority');
    }

    /**
     * 获取回调
     *
     * @param mixed $callback 回调，支持 闭包，类方法"class@method"
     * @return mixed
     * @throws \Exception
     */
    protected function callback($callback)
    {
        // 类方法：字符串且包含@符号
        if (is_string($callback) && strpos($callback, '@')) {
            $callback = explode('@', $callback);
            return [app('\\' . $callback[0]), $callback[1]];
        }

        // 闭包函数
        if (is_callable($callback)) {
            return $callback;
        }

        throw new Exception('$callback is not a Callable', 1);
    }

    /**
     * 触发动作或者滤器
     *
     * @param string $hook 滤器名称
     * @param array $args 参数
     */
    abstract function fire(string $hook, array $args);
}

<?php
namespace App\Hook;

use Illuminate\Support\Traits\Macroable;

abstract class Base
{
    use Macroable;
    
	/**
	 * 存储hook
	 * 
	 * @var array
	 */
	protected $listeners = null;

	/**
	 * 初始化 listeners
	 */
	public function __construct() {
		$this->listeners = collect([]);
	}

	/**
	 * 监听Hook
	 * 
	 * @param string  $hook      钩子名称
	 * @param mixed   $callback  回调
	 * @param integer $priority  执行优先级，默认为50，越小越靠前执行
	 * @return mixed
	 */
	public function listen($hook, $callback, $priority = 50)
	{
		$this->listeners->push([
            'hook'      => $hook,
            'callback'  => $callback,
            'priority'  => $priority
		]);

		return $this;
	}

    /**
     * 删除hook
     *
     * @param string $hook 钩子名称
     * @param mixed  删除键名（一般为回调名称）
     * @return mixed
     */
    public function forget($hook, $callback=null)
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
	 * @return array
	 */
	public function listeners($hook)
	{
		return $this->listeners->where('hook', $hook)->sortBy('priority');
	}

	/**
	 * 获取回调
	 * 
	 * @param  mixed $callback Callback
	 * @return mixed A closure, an array if "class@method" or a string if "function_name"
	 */
	protected function callback($callback)
	{
		// 类函数：字符串且包含@符号
		if (is_string($callback) && strpos($callback, '@')) {
			$callback = explode('@', $callback);
			return array(app('\\' . $callback[0]), $callback[1]);
		}

		// 闭包函数
		if (is_callable($callback)) {
			return $callback;
		}
		
		throw new Exception('$callback is not a Callable', 1);
	}

	/**
	 * Fires a new action
	 * @param  string $action Name of action
	 * @param  array  $args   Arguments passed to the action
	 */
	abstract function fire($action, $args);
}

<?php

namespace Modules\Core\Support;

abstract class Hook {

	/**
	 * 存储hook
	 * 
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * 监听Hook
	 * 
	 * @param string  $hook      钩子名称
	 * @param mixed   $callback  回调函数或者回调的
	 * @param integer $priority  执行优先级，默认为20，越小越靠前执行
	 * @return mixed
	 */
	public function listen($hook, $callback, $priority = 20)
	{
		$i = 0;

		$uniquePriority = $priority;

		do
		{
			if( isset( $this->listeners[$hook][$uniquePriority] ) )
			{
				$i += 0.1;
				$uniquePriority = $priority + $i;
			}

		} while( isset( $this->listeners[$hook][$uniquePriority] ) );

		$this->listeners[$hook][$uniquePriority] = $callback;
	}

	/**
	 * 获取排序过的监听器
	 * 
	 * @return array
	 */
	public function listeners($hook)
	{
		$listeners = isset($this->listeners[$hook]) ? $this->listeners[$hook] : [];

		if ($listeners){			
			// 排序
			uksort($listeners, function($a,$b){
				return strnatcmp($a,$b);
			});
		}

		return $listeners;
	}

	/**
	 * 获取回调
	 * 
	 * @param  mixed $callback Callback
	 * @return mixed A closure, an array if "class@method" or a string if "function_name"
	 */
	protected function callback($callback)
	{
		if (is_string($callback) && strpos($callback, '@')) {

			$callback = explode('@', $callback);
			
			return array(app('\\' . $callback[0]), $callback[1]);

		} else if (is_callable($callback)) {

			return $callback;

		} else {
			throw new Exception('$callback is not a Callable', 1);
		}
	}

	/**
	 * Fires a new action
	 * @param  string $action Name of action
	 * @param  array  $args   Arguments passed to the action
	 */
	abstract function fire($action, $args);
}

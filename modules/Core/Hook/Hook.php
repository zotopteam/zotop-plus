<?php

namespace Modules\Core\Hook;

abstract class Hook {

	/**
	 * 存储hook
	 * 
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * 添加一个Hook
	 * 
	 * @param string  $hook      Hook name
	 * @param mixed   $callback  Function to execute
	 * @param integer $priority  Priority of the action
	 * @param integer $arguments Number of arguments to accept
	 */
	public function listen($hook, $callback, $priority = 20, $arguments = 1)
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

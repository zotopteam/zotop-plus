<?php

namespace Modules\Core\Support;

class Action extends Hook {

	/**
	 * 过滤器钩子触发
	 * 
	 * @param  string $hook  钩子名称
	 * @param  mixed $param  值
	 * @return mixed         总是返$param
	 */
	public function fire($hook, $param=null)
	{
		
		if ( $callbacks = $this->listeners($hook) ) {

			// 获取全部参数
			$args = func_get_args();

			// 调用全部回调
			foreach($callbacks as $callback) {
				call_user_func_array($this->callback($callback), array_slice($args, 1));
			}			
		}

	}

}

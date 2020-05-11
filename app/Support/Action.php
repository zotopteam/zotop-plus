<?php

namespace App\Support;

use App\Support\Hook;

class Action extends Hook
{

	/**
	 * 过滤器钩子触发
	 * 
	 * @param  string $hook  钩子名称
	 * @param  mixed $param  值
	 * @return mixed         总是返$param
	 */
	public function fire($hook, $param=null)
	{
		$args = func_get_args();

		$this->listeners($hook)->each(function ($listener) use($args) {
			call_user_func_array($this->callback($listener['callback']), array_slice($args, 1));
		});
	}

}

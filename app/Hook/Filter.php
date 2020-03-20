<?php

namespace App\Hook;

use App\Hook\Base;

class Filter extends Base
{

	/**
	 * 返回值
	 * @var null
	 */
	protected $value = null;

	/**
	 * 过滤器钩子触发
	 * 
	 * @param  string $hook  钩子名称
	 * @param  mixed $param  值
	 * @return mixed
	 */
	public function fire($hook, $param)
	{
		$args = func_get_args();

		$this->value = $param;

		$this->listeners($hook)->each(function ($listener) use($args) {
			$args[1] = $this->value;
			$this->value = call_user_func_array($this->callback($listener['callback']), array_slice($args, 1));
		});

		return $this->value;
	}
}

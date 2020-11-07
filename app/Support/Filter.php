<?php

namespace App\Support;

class Filter extends Hook
{

    /**
     * 返回值
     *
     * @var null
     */
    protected $value = null;

    /**
     * 滤器钩子触发
     *
     * @param string $hook 钩子名称
     * @param mixed $args 值
     * @return mixed 总是返回对param的修改
     * @throws \Exception
     */
    public function fire(string $hook, array $args)
    {
        $args = func_get_args();

        $this->value = $args;

        $this->listeners($hook)->each(function ($listener) use ($args) {
            $args[1] = $this->value;
            $this->value = call_user_func_array($this->callback($listener['callback']), array_slice($args, 1));
        });

        return $this->value;
    }
}

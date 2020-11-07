<?php

namespace App\Support;

class Action extends Hook
{

    /**
     * 动作钩子触发
     *
     * @param string $hook 钩子名称
     * @param mixed $args 值
     * @return void
     * @throws \Exception
     */
    public function fire(string $hook, $args = null)
    {
        $args = func_get_args();

        $this->listeners($hook)->each(function ($listener) use ($args) {
            call_user_func_array($this->callback($listener['callback']), array_slice($args, 1));
        });
    }
}
